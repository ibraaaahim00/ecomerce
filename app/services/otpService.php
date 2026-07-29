<?php

namespace App\Services;

use App\Repositories\OtpRepository;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\InvalidOtpException;

class OtpService
{
    public function __construct(
        private OtpRepository $otpRepository
    ) {
    }

    public function verifyOtp(array $data)
    {
        $record = $this->otpRepository->findOtp(
            $data['email'],
            $data['otp']
        );

        if (!$record) {
            throw new InvalidOtpException();
        }

        return [
            'success' => true,
            'message' => 'OTP Verified',
        ];
    }

    public function resendOtp(array $data)
    {
        $otp = mt_rand(100000, 999999);

        $this->otpRepository->deleteOtp($data['email']);

        $this->otpRepository->storeOtp([
            'email' => $data['email'],
            'otp' => $otp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::raw("Your new OTP is: $otp", function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Resend OTP');
        });

        return [
            'success' => true,
            'message' => 'OTP resent successfully',
        ];
    }
}
