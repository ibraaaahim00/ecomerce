<?php

namespace App\Exceptions;

use Exception;

class EmailNotFoundException extends Exception
{
    protected $code=404;
    protected $message="email not found";
}
