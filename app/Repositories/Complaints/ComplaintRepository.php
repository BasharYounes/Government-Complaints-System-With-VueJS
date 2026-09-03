<?php

namespace App\Repositories\Complaints;

use App\Models\Complaint;
use App\Attributes\Transactional;
use Illuminate\Validation\ValidationException;

class ComplaintRepository
{
    public function createComplaint(array $data,string $referenceNumber): Complaint
    {
        // dd(auth()->guard('api')->id());
        $data['user_id'] = auth()->id();
        $data['reference_number'] = $referenceNumber;
        return Complaint::create($data);
    }

    public function getComplaintById($id): Complaint
    {
        $complaint = Complaint::with(['governmentEntity','attachments'])->where('id', $id)->first();
        if (! $complaint) {
            abort(response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع',
                'errors' => 'الشكوى غير موجودة'
            ], 404));
        }

        return $complaint;
    }

    #[Transactional]
    public function updateComplaint($id, array $data): Complaint
    {
        $complaint = Complaint::where('id', $id)
        ->lockForUpdate()
        ->firstOrFail();

        $complaint->update($data);

        return $complaint->fresh();
    }

    #[Transactional]
    public function updateUserComplaint(
        int $complaintId,
        int $userId,
        array $data
    ): Complaint {

        $complaint = Complaint::query()
            ->whereKey($complaintId)
            ->where('user_id', $userId)
            ->lockForUpdate()
            ->first();

        if (! $complaint) {
            abort(404);
        }

        if ($complaint->status !== 'new') {
            throw ValidationException::withMessages([
                'complaint' =>
                    'لا يمكن تعديل الشكوى بعد بدء معالجتها.',
            ]);
        }

        $complaint->update($data);

        return $complaint->fresh([
            'governmentEntity',
            'attachments',
        ]);
    }

    public function deleteComplaint($id): void
    {
        $complaint = $this->getComplaintById($id);
        $complaint->delete();
    }

    public function getComplaintsByUser()
    {
        return auth()->user()
            ->complaints()
            ->with([
                'governmentEntity:id,name',
                'attachments',
            ])
            ->latest()
            ->get();
    }

    public function findByReferenceNumberForUser(
        string $referenceNumber,
        int $userId
    ): ?Complaint {
        return Complaint::query()
            ->where('reference_number', $referenceNumber)
            ->where('user_id', $userId)
            ->first();
    }

    public function allComplaint()
    {
        return Complaint::with('attachments')->where('government_entity_id', auth()->user()->government_entity_id)->paginate(10);
        //['logs.details','attachments','user','governmentEntity']
    }

    public function getallComplaintsWithLogsDetails()
    {
        return Complaint::with(['logs.details','attachments','user','governmentEntity'])->paginate(10);
    }
}
