<?php

namespace App\Http\Controllers;

use App\Aspects\TransactionAspect;
use App\Http\Requests\Attachments\AttachmentRequest;
use App\Http\Requests\Complaints\ComplaintUpdateRequest;
use App\Repositories\Attachments\AttachmentRepository;
use App\Repositories\Complaints\ComplaintRepository;
use App\Repositories\GovernementEntities\GovernmentEntityRepository;
use App\Repositories\ReferanceNumberRepository\ReferanceNumberRepository;
use App\Services\Attachments\AttachmentService;
use App\Services\EmployeeComplaintService;
use App\Http\Requests\Complaints\ComplaintRequest;
use App\Http\Requests\Complaints\TrackComplaintRequest;
use Illuminate\Http\RedirectResponse;
use Cache;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\NotificationResource;

class ComplaintController extends Controller
{
    // Using the ApiResponse trait for standardized API responses
    /**
     * Constructor to initialize repositories and services.
     */
    public function __construct(protected AttachmentService $attachmentService,
    protected AttachmentRepository $attachmentRepository,
    protected ReferanceNumberRepository $referenceNumberRepository,
    protected GovernmentEntityRepository $governmentEntityRepository,
    protected ComplaintRepository $complaintRepository,
    protected EmployeeComplaintService $complaintService
    )
    {}
    /**
     * Display a listing of the resource.
     */

    public function home()
    {
        return Inertia::render('User/HomePage', $this->getHomePayload());
    }

    private function getHomePayload(): array
    {
        $user = auth()->user();

        $complaints = $user->complaints()
            ->with('governmentEntity:id,name')
            ->latest()
            ->get();

        $stats = $this->buildHomeStats($complaints);

        $recentComplaints = $complaints
            ->take(5)
            ->map(fn ($complaint) => [
                'id' => $complaint->id,
                'tracking_number' => $complaint->reference_number,
                'subject' => $complaint->type,
                'department' => $complaint->governmentEntity?->name ?? 'غير محدد',
                'status' => $this->mapComplaintStatusForHome($complaint->status),
                'created_at' => $complaint->created_at?->format('Y-m-d'),
            ])
            ->values();

        $notifications = $user
            ->notifications()
            ->latest()
            ->limit(20)
            ->get();

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'stats' => $stats,
            'recentComplaints' => $recentComplaints,
            'notifications' =>
                NotificationResource::collection(
                    $notifications
                )->resolve(),
        ];
    }

    private function buildHomeStats($complaints): array
    {
        return [
            'total' => $complaints->count(),
            'pending' => $complaints->whereIn('status', ['new', 'in_progress'])->count(),
            'resolved' => $complaints->where('status', 'completed')->count(),
            'rejected' => $complaints->where('status', 'rejected')->count(),
        ];
    }

    private function mapComplaintStatusForHome(string $status): string
    {
        return match ($status) {
            'new' => 'pending',
            'in_progress' => 'processing',
            'completed' => 'resolved',
            default => $status,
        };
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(ComplaintRequest $complaintRequest,AttachmentRequest $attachmentRequest)
    {
        $governmentEntity = $this->governmentEntityRepository->getCodeById($complaintRequest->government_entity_id);

        $referenceNumber = TransactionAspect::handle(
            $this->referenceNumberRepository,
            'generateReferenceNumber',
            [$governmentEntity->code]
        );

        $complaint = $this->complaintRepository->createComplaint($complaintRequest->validated(), $referenceNumber);

        if ($attachmentRequest->hasFile('file')) {
            $file = $attachmentRequest->file('file');
            $data = $this->attachmentService->extractInfoFromFile($attachmentRequest['file']);
            $path = $file->store('attachments','public');
            dispatch(new \App\Jobs\ProcessComplaintAttachmentJob(
                $complaint->id,
                $path,
                auth()->user()->id,
                $data,

            ));
        }

    return redirect()
        ->route('user.home')
        ->with('success', 'Complaint created successfully');
    }

    /**
     * Display the specified resource.
     */
   public function show($id)
    {
        $complaint = $this->complaintRepository->getComplaintById($id);

        if ((int) $complaint->user_id !== (int) auth()->id()) {
            return redirect()
                ->route('user.complaints')
                ->with('error', 'الشكوى غير موجودة أو لا تملك صلاحية الوصول إليها.');
        }

        return Inertia::render('User/Complaint/ComplaintDetails', [
            'complaint' => [
                'id' => $complaint->id,
                'reference_number' => $complaint->reference_number,
                'type' => $complaint->type,
                'description' => $complaint->description,
                'status' => $complaint->status,
                'location' => $complaint->location,

                'government_entity' => $complaint->governmentEntity
                    ? [
                        'id' => $complaint->governmentEntity->id,
                        'name' => $complaint->governmentEntity->name,
                    ]
                    : null,

                'attachments' => $complaint->attachments
                    ->map(function ($attachment) {
                        return [
                            'id' => $attachment->id,
                            'file_name' => $attachment->file_name,
                            'file_path' => $attachment->file_path,
                            'mime_type' => $attachment->mime_type,
                            'file_size' => $attachment->file_size,

                            'url' => Storage::disk('public')->url(
                                $attachment->file_path
                            ),
                        ];
                    })
                    ->values(),

                'created_at' => $complaint->created_at?->format('Y-m-d H:i'),
                'updated_at' => $complaint->updated_at?->format('Y-m-d H:i'),
            ],
        ]);
    }
    /**
     * Check if the complaint is being edited by another employee.
     */
    public function edit($id)
    {

        $User = auth()->user();
        $lockKey = 'complaint_update_'.$id;


        $lockOwner = Cache::get($lockKey);

        if ($lockOwner && $lockOwner !== $User->id) {
            return $this->error('This complaint is currently being edited by another employee.',null, 403);
        }

        Cache::put($lockKey, $User->id, now()->addMinutes(10));

        $complaint = $this->complaintRepository->getComplaintById($id);

    return $this->success('Complaint allowed editing', $lockKey, 200);
    }
    /**
     * Update the specified resource in storage after checking for edit locks.
     */
    public function update(
        ComplaintUpdateRequest $request,
        int $id
    ) {
        $complaint =
            $this->complaintRepository
                ->updateUserComplaint(
                    $id,
                    auth()->id(),
                    $request->validated()
                );

        return redirect()
            ->route(
                'user.complaints.show',
                ['id' => $complaint->id]
            )
            ->with(
                'success',
                'تم تحديث الشكوى بنجاح.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->complaintRepository->deleteComplaint($id);
    return $this->success('Complaint deleted successfully', null, 200);
    }

    public function addAttachment(AttachmentRequest $attachmentRequest,$id)
    {
        $file = $attachmentRequest->file('file');
        $data = $this->attachmentService->extractInfoFromFile($attachmentRequest['file']);
        $path = $file->store('attachments','public');
        dispatch(new \App\Jobs\ProcessComplaintAttachmentJob(
            $id,
            $path,
            auth()->user()->id,
            $data,

        ));

    return $this->success('Attachments uploaded successfully', null, 201);
    }

    public function getComplaintsforUser()
    {
        $complaints = $this->complaintRepository->getComplaintsByUser();

        return Inertia::render('User/Complaint/MyComplaints', [
            'complaints' => $complaints,
        ]);
    }

    public function track(
        TrackComplaintRequest $request
    ): RedirectResponse {
        $complaint = $this->complaintRepository
            ->findByReferenceNumberForUser(
                $request->validated('reference_number'),
                auth()->id()
            );

        if (! $complaint) {
            return back()->withErrors([
                'reference_number' =>
                    'لم يتم العثور على شكوى بهذا الرقم.',
            ]);
        }

        return redirect()->route(
            'user.complaints.show',
            $complaint->id
        );
    }

}
