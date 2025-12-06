<?php

namespace App\Exceptions;

use Exception;

class UserCreationException extends Exception
{
    protected $message = 'Failed to create user';
    protected $code = 500;

    public function __construct(string $message, int $code)
    {
        parent::__construct($message ?? $this->message , $code ?? $this->code ,);
    }
}
