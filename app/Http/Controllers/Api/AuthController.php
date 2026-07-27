<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\OtpService;
use App\Services\PasswordResetService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private OtpService $otpService,
        private PasswordResetService $passwordResetService
    ) {
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);

        return response()->json(
            $this->authService->register($validatedData)
        );
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            return response()->json(
                $this->authService->login($validatedData)
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        return response()->json(
            $this->authService->logout($request->user())
        );
    }

    public function forgetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
        ]);

        return response()->json([
            'message' => $this->passwordResetService->forgetPassword($validatedData),
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        return response()->json(
            $this->otpService->verifyOtp($validatedData)
        );
    }

    public function resendOtp(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        return response()->json(
            $this->otpService->resendOtp($validatedData)
        );
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        return response()->json(
            $this->passwordResetService->resetPassword($validatedData)
        );
    }
}
