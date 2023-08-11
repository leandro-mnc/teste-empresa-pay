<?php

namespace App\Domain\Transaction\Services;

use App\Infrastructure\Request\RequestClient;
use App\Infrastructure\Request\GuzzleClient;
use Psr\Log\LoggerInterface;
use Throwable;

class TransferNotificationService
{
    private const NOTIFICATION__URL = 'http://o4d9z.mocklab.io/notify';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly RequestClient $client
    ) {
    }

    public function notifyPayee(string $name, string $email, string $description): bool
    {
        try {
            $response = $this->client->post(
                self::NOTIFICATION__URL,
                ['name' => $name, 'email' => $email, 'description' => $description],
                GuzzleClient::DATA_JSON
            );

            $data = json_decode($response->getBody()->getContents());

            return true;
        } catch (Throwable $ex) {
            $this->logger->error($ex);

            return false;
        }
    }
}
