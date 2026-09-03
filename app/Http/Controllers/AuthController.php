<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Exceptions\CodeSendingException;
use App\Exceptions\InvalidCodeException;
use App\Http\Requests\EmailAndCodeRequest;
use App\Http\Requests\EmailRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\Web\AdminRepository;
use App\Repositories\ComplaintEmployeeRepository;
use App\Repositories\UserRepository;
use App\Services\CasheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserInformationRequest;
use App\Services\AuthService;
use App\Services\GenerateCode;
use App\Services\LoginAttemptService;
use App\Http\Requests\StoreFcmTokenRequest;
use App\Http\Requests\AuthEmployee\SignInRequest as LoginEmployeeRequest;
use App\Http\Requests\AuthEmployee\SignUpRequest as RegisterEmployeeRequest;
use App\Http\Requests\AuthAdmin\SignUpRequest as RegisterAdminRequest;
use App\Http\Requests\AuthAdmin\SignInRequest as LoginAdminRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;


class AuthController extends Controller
{

    public function __construct(
        protected AuthService $authService,
        protected GenerateCode $codeService,
        protected UserRepository $userRepository,
        protected CasheService $casheService,
        protected ComplaintEmployeeRepository $complaintEmployeeRepository,
        protected AdminRepository $adminRepository,
        protected LoginAttemptService $loginAttemptService,
    ) {}

    /**
     * Register new user and send verification code
     *
     * @param RegisterRequest $registerRequest
        * @return RedirectResponse
     *
     * @throws \Exception
     */
    public function RegisterUser(RegisterRequest $registerRequest)
    {
        $user = $this->authService->registerUser($registerRequest);

        $code = $this->codeService->generateCode($user);

        \Log::info('✅ User Registered', [
            'id' => $user->id,
            'email' => $user->email,
            'code' => $code
        ]);
        event(new UserRegistered($user, $code));

        return redirect()
            ->route('user.verify-code')
            ->with('success', 'تم إنشاء حسابك بنجاح. تم إرسال كود التحقق إلى بريدك الإلكتروني')
            ->with('email', $user->email);
    }

    public function registerEmployee(RegisterEmployeeRequest $registerEmployeeRequest)
    {
        $employee = $this->complaintEmployeeRepository->createEmployee($registerEmployeeRequest->validated());
        $employee->assignRole('employee');

        if (Auth::guard('employee')->attempt($registerEmployeeRequest->only('email', 'password'))) {
            $registerEmployeeRequest->session()->regenerate();
        return $this->success('Employee Registered Successfully', [
                'employee' => $employee
                ]);
        }
    return $this->error('Failed to register employee', null, 500);
    }

    public function loginEmployee(LoginEmployeeRequest $loginEmployeeRequest)
    {
        $credentials = $loginEmployeeRequest->only('email', 'password');

        if (Auth::guard('employee')->attempt($credentials)) {
            $loginEmployeeRequest->session()->regenerate();
            return response()->json([
                'message' => 'Logged in as employee',
                'employee' => Auth::guard('employee')->user()
            ]);
        }

    return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logoutEmployee(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Logged out Successfully']);
    }

    public function registerAdmin(RegisterAdminRequest $registerAdminRequest)
    {
            $admin = $this->adminRepository->createAdmin($registerAdminRequest->validated());
            $admin->assignRole('super_admin');

            if (Auth::guard('admin')->attempt($registerAdminRequest->only('email', 'password'))) {
                $registerAdminRequest->session()->regenerate();
            return $this->success(
                    'Admin Registered Successfully',
                    [
                    'admin' => $admin
                    ],
                    201
                );
            }
            return $this->error('Failed to register admin', null, 500);

    }

    public function loginAdmin(LoginAdminRequest $loginAdminRequest)
    {
        $admin = $this->adminRepository->findByEmail($loginAdminRequest->email);

        if ($admin && Auth::guard('admin')->attempt($loginAdminRequest->only('email', 'password'))) {
            $loginAdminRequest->session()->regenerate();
            return $this->success(
                'Logged in as admin',
                [
                    'admin' => $admin
                ]
            );
        }
        return $this->error('Informations are not Correct',null, 401);
    }

    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->success('Logged out Successfully');
    }


    public function EditInformation(UpdateUserInformationRequest $request)
    {
        $this->userRepository->update(
            auth()->user(),
            $request
        );

        return redirect()
            ->route('user.profile')
            ->with('success', 'تم تحديث معلومات الملف الشخصي بنجاح');
    }

    /**
     * Authenticate user and send verification code
     *
     * @param LoginRequest $request
     * @return Response
     *
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        try {
            $user = $this->userRepository->findByEmail($request->email);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return Inertia::render('User/Auth/LogIn', [
                'email' => $request->email,
                'flash' => [
                    'error' => 'البريد الإلكتروني غير موجود في النظام'
                ]
            ])->withInput();
        }

        // التحقق من حالة الحساب
        if ($this->loginAttemptService->isAccountLocked($user)) {
            return Inertia::render('User/Auth/LogIn', [
                'email' => $request->email,
                'flash' => [
                    'error' => 'تم إيقاف حسابك مؤقتًا بسبب محاولات تسجيل دخول فاشلة متعددة. يرجى المحاولة مرة أخرى لاحقًا.'
                ]
            ])->withInput();

        }

        try {
            $this->authService->checkPassword($request->password, $user->password);

            // إعادة تعيين المحاولات الفاشلة عند نجاح التحقق من كلمة المرور
            $this->loginAttemptService->resetFailedAttempts($user);

            $code = $this->codeService->generateCode($user);

            if (!event(new UserRegistered($user, $code))) {
                throw new CodeSendingException('فشل إرسال الكود');
            }

            return Inertia::render('User/Auth/VerifyCode', [
                'email'   => $user->email,
                'flash'   => [
                    'success' => 'تم إرسال كود التحقق إلى بريدك الإلكتروني'
                ]
            ]);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // تسجيل محاولة فاشلة
            $this->loginAttemptService->recordFailedAttempt($user);

            // إعادة تحميل البيانات للحصول على القيم المحدثة
            $user->refresh();
            $remainingAttempts = $this->loginAttemptService->getMaxAttempts() - ($user->failed_login_attempts ?? 0);
            $message = $remainingAttempts > 0
                ? "كلمة المرور غير صحيحة. لديك {$remainingAttempts} محاولات متبقية."
                : "تم إيقاف حسابك بسبب محاولات تسجيل دخول فاشلة متعددة.";

            return  Inertia::render('User/Auth/LogIn', [
                'email' => $request->email,
                'flash' => [
                    'error' => $message
                ]
            ])->withInput();

        }
    }

    /**
     * Resend verification code to user's email
     *
     * @param Request $request
     * @return Response
     *
     * @throws \Exception
     */
    public function ResendCode(EmailRequest $request)
    {

        $user = $this->userRepository->findByEmail($request->email);

        $code = $this->codeService->generateCode($user);

        event(new UserRegistered($user, $code));

        return Inertia::render('User/Auth/VerifyCode', [
            'email' => $user->email,
            'flash' => [
                'success' => 'تم إرسال كود التحقق إلى بريدك الإلكتروني'
            ]
        ]);

    }

    /**
     * Verify user's code and activate account
     *
     * @param Request $request
        * @return \Illuminate\Http\RedirectResponse|Response
     *
     * @throws \Exception
     */
    public function verifyCode(EmailAndCodeRequest $request)
    {

        $user = $this->userRepository->findByEmail($request->email);

        $storedCode = $this->casheService->getCodeFromCashe($user);

        if (!$storedCode || $storedCode != $request->code) {
            throw new InvalidCodeException();
        }

        $this->userRepository->update($user, ['email_verified_at' => now()]);

        $this->casheService->forgetCodeFromCashe($user);

        Auth::guard('web')->login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('user.home')
            ->with('success', 'تم التحقق من الكود وتفعيل حسابك بنجاح');
    }

    /**
     * Refresh authentication token
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function refreshToken()
    {

        $user = auth()->user();

        $this->userRepository->deleteUserToken($user);

        $newToken = $this->userRepository->createToken($user);

        return $this->success("null", $newToken);
    }

    public function logout()
    {
        $user = auth()->user();
        // dd($user);
        $this->userRepository->deleteUserToken($user);
        return redirect()->route('user.log-in')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }

    public function getUser()
    {
        $user = auth()->user();

        return Inertia::render('User/Profile/Profile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image,
                'email_verified' => ! is_null($user->email_verified_at),
            ],
        ]);
    }

    public function storeFCM_Token(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        $this->authService->storeFCM(auth()->user(),$request->input('fcm_token'));

        return $this->success("Token saved successfully");
    }

    public function storeFcmToken(
    StoreFcmTokenRequest $request
    ) {

        $this->authService->storeFCM(
            $request->user(),
            $request->validated('fcm_token')
        );


        return $this->success(
            'تم تسجيل جهاز الإشعارات بنجاح'
        );
    }
}
