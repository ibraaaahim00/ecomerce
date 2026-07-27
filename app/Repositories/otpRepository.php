<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class OtpRepository
{
    public function findOtp(string $email, string $otp)
    {
        return DB::table('password_otps')
            ->where('email', $email)
            ->where('otp', $otp)
            ->first();
    }

    public function deleteOtp(string $email)
    {
        DB::table('password_otps')
            ->where('email', $email)
            ->delete();
    }

    public function storeOtp(array $data)
    {
        DB::table('password_otps')->insert($data);
    }
}
