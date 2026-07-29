<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $code=404;
    protected $message="User not found";
}
