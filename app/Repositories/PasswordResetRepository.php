<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\InvalidOtpException;
use App\Exceptions\EmailNotFoundException;

class PasswordResetRepository
{
    public function saveOtp($email, $otp)
    {
        DB::table('password_otps')
            ->where('email', $email)
            ->delete();

        DB::table('password_otps')->insert([
            'email'      => $email,
            'otp'        => $otp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function resetPassword(array $data)
    {
        $record = DB::table('password_otps')
            ->where('email', $data['email'])
            ->where('otp', $data['otp'])
            ->first();

        if (!$record) {
            throw new InvalidOtpException();
        }

        $user = User::firstWhere('email', $data['email']);

        if (!$user) {
            throw new EmailNotFoundException();
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        DB::table('password_otps')
            ->where('email', $data['email'])
            ->delete();

        return [
            'success' => true,
            'message' => 'Password reset successfully',
        ];
    }

    public function updatePassword(string $email, string $password)
    {
        return User::where('email', $email)->update([
            'password' => Hash::make($password),
        ]);
    }

    public function deleteResetToken(string $email)
    {
        DB::table('password_otps')
            ->where('email', $email)
            ->delete();
    }
}
