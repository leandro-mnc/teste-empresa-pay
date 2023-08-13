<?php

declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    private string $message;

    /**
     * @var array|object|null
     */
    private $data;

    private ?ActionError $error;

    public function __construct(
        $message = '',
        $data = null,
        int $statusCode = 200,
        ?ActionError $error = null
    ) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
        $this->error = $error;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array|null|object
     */
    public function getData()
    {
        return $this->data;
    }

    public function getError(): ?ActionError
    {
        return $this->error;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $payload = [
            'statusCode' => $this->statusCode,
        ];

        if ($this->message !== '') {
            $payload['message'] = $this->message;
        }

        if ($this->error !== null) {
            $payload['success'] = false;
            $payload['error'] = $this->error;
        } elseif ($this->data !== null) {
            $payload['success'] = true;
            $payload['data'] = $this->data;
        }

        return $payload;
    }
}
