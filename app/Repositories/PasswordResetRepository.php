<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetRepository
{
    public function saveOtp($email, $otp)
    {
        DB::table('password_otps')
            ->where('email', $email)
            ->delete();

        DB::table('password_otps')->insert([
            'email' => $email,
            'otp' => $otp,
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
            return [
                'success' => false,
                'message' => 'Invalid OTP',
            ];
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found',
            ];
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
