<?php

namespace App\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    protected $code=401;
    protected $message="Invalid credentials";
}
