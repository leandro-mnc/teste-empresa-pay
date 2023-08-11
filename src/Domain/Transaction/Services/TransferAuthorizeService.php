<?php

namespace App\Domain\Transaction\Services;

use App\Infrastructure\Request\RequestClient;
use Psr\Log\LoggerInterface;
use Throwable;

class TransferAuthorizeService
{
    private RequestClient $client;

    private string $url = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';

    public function __construct(private readonly LoggerInterface $logger)
    {
        $this->client = new RequestClient();
    }

    public function isPayerAuthorizatedToTransfer(): bool
    {
        try {
            $response = $this->client->get($this->url, null);

            $data = json_decode($response->getBody()->getContents());

            return $data->message === 'Autorizado';
        } catch (Throwable $ex) {
            $this->logger->error($ex);
            return false;
        }
    }
}
