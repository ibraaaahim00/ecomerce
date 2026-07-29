<?php

namespace App\Services;

use App\Repositories\PasswordResetRepository;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Exceptions\EmailNotFoundException;
class PasswordResetService
{
    protected $passwordResetRepository;

    public function __construct(PasswordResetRepository $passwordResetRepository)
    {
        $this->passwordResetRepository = $passwordResetRepository;
    }

    public function forgetPassword(array $data)
    {
        $user = User::firstWhere('email', $data['email']);
        if (!$user) {
            throw new EmailNotFoundException();
        }

        $otp = mt_rand(100000,999999);

        $this->passwordResetRepository->saveOtp(
            $data['email'],
            $otp
        );

        Mail::raw("Your OTP is: $otp", function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Password Reset OTP');
        });

        return [
            'success' => true,
            'message' => 'OTP sent successfully'
        ];
    }
    public function resetPassword(array $data)
    {
        return $this->passwordResetRepository->resetPassword($data);
    }
}
