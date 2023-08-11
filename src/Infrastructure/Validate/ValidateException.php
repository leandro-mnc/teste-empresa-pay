<?php

namespace App\Infrastructure\Validate;

use Exception;
use Throwable;

class ValidateException extends Exception
{
    private array $messages;

    public function __construct(array $messages, string $message, int $code = 402, Throwable | null $throwable = null)
    {
        parent::__construct($message, $code, $throwable);
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}
