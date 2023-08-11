<?php

namespace App\Infrastructure\Request;

use Psr\Http\Message\ResponseInterface;

class RequestClient implements RequestClientInterface
{
    private GuzzleClient $client;

    public function __construct(string $baseUri = '', int $timeout = 30)
    {
        $this->client = new GuzzleClient($baseUri, $timeout);
    }

    public function delete($uri, ?array $params = null, ?array $headers = null): ResponseInterface
    {
        return $this->client->delete($uri, $params, $headers);
    }

    public function get($uri, ?array $params = null, ?array $headers = null): ResponseInterface
    {
        return $this->client->get($uri, $params, $headers);
    }

    public function post(
        $uri,
        array $data = [],
        string $dataType = GuzzleClient::DATA_FORM,
        ?array $headers = null
    ): ResponseInterface {
        return $this->client->post($uri, $data, $dataType, $headers);
    }

    public function put(
        $uri,
        array $data = [],
        string $dataType = GuzzleClient::DATA_FORM,
        ?array $headers = null
    ): ResponseInterface {
        return $this->client->put($uri, $data, $dataType, $headers);
    }
}
