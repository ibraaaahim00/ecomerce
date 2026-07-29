<?php

namespace App\Http\Controllers\Api;


use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use App\Exceptions\UnauthorizedException;
use App\Http\Resources\AuthResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;use App\Services\AuthService;
use App\Services\OtpService;
use App\Services\PasswordResetService;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private AuthService          $authService,
        private OtpService           $otpService,
        private PasswordResetService $passwordResetService
    )
    {
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return new AuthResource($result);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return new AuthResource($result);
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout($request->user());

        return new MessageResource($result);
    }

    public function forgetPassword(ForgotPasswordRequest $request)
    {
        $result = $this->passwordResetService->forgetPassword(
            $request->validated()
        );

        return new MessageResource($result);
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = $this->otpService->verifyOtp(
            $request->validated()
        );

        return new MessageResource($result);
    }

    public function resendOtp(ResendOtpRequest $request)
    {
        $result = $this->otpService->resendOtp(
            $request->validated()
        );

        return new MessageResource($result);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->passwordResetService->resetPassword($request->validated());

        return new MessageResource($result);


    }
}
