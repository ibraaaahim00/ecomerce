<?php

namespace App\Exceptions;

use Exception;

class OtpExpiredException extends Exception
{
    protected $code=500;
    protected $message="OTP expired";
}
