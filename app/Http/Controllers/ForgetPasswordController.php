<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;

use App\Exceptions\InvalidCodeException;
use App\Http\Requests\EmailAndCodeRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\EmailRequest;

use Hash;

use App\Repositories\UserRepository;

use App\Services\CasheService;
use App\Services\GenerateCode;
use Inertia\Inertia;

class ForgetPasswordController extends Controller
{

    public function __construct(
        protected GenerateCode $codeService,
        protected UserRepository $userRepository,
        protected CasheService $casheService)
        {}

    public function forgotPassword(EmailRequest $request)
    {
            $user = $this->userRepository->findByEmail($request->email);

            $code = $this->codeService->generateCode($user);

            event(new UserRegistered($user, $code)) ;

        return Inertia::render('User/Auth/CheckCode', [
            'email' => $user->email,
            'flash' => [
                'success' => 'تم إرسال كود التحقق إلى بريدك الإلكتروني'
            ]
        ]);
    }

    public function checkCode(EmailAndCodeRequest $request)
    {

        $user = $this->userRepository->findByEmail($request->email);

        $storedCode = $this->casheService->getCodeFromCashe($user);

        if (!$storedCode || $storedCode != $request->code) {
           throw new InvalidCodeException();
        }

        $this->casheService->forgetCodeFromCashe($user);

        // $this->userRepository->deleteUserToken($user);

        // $token = $this->userRepository->createToken($user);

        return Inertia::render('User/Auth/ResetPassword', [
            'email' => $user->email,
            'flash' => [
                'success' => 'تم التحقق من الكود بنجاح، يمكنك الآن إعادة تعيين كلمة السر'
            ]
        ]);
    }

    public function resetPassword(PasswordRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);

        $this->userRepository->update($user,['password' => Hash::make($request->password)]);

       return Inertia::render('User/HomePage', [
        'flash' => [
            'success' => 'تم إعادة تعيين كلمة السر بنجاح، يمكنك الآن تسجيل الدخول'
        ]
       ]);
    }
}
