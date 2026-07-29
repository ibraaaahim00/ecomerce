<?php

namespace App\Exceptions;

use Exception;

class InvalidOtpException extends Exception
{
    protected $code=404;
    protected $message="Invalid OTP";
}
