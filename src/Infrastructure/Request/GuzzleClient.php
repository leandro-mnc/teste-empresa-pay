<?php

namespace App\Infrastructure\Request;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient implements RequestClientInterface
{
    private Client $client;

    public const DATA_BODY = 'body';
    public const DATA_FORM = 'form_params';
    public const DATA_MULTIPART = 'multipart';
    public const DATA_JSON = 'json';

    public function __construct(string $baseUri = '', int $timeout = 10)
    {
        $options = ['timeout' => $timeout];

        if ($baseUri !== '') {
            $options['base_uri'] = $baseUri;
        }

        $this->client = new Client($options);
    }

    public function delete($uri, ?array $params = null, ?array $headers = null): ResponseInterface
    {
        return $this->request('DELETE', $uri, $params, null, $headers);
    }

    public function get($uri, ?array $params = null, ?array $headers = null): ResponseInterface
    {
        return $this->request('GET', $uri, $params, null, $headers);
    }

    public function post(
        $uri,
        array $data = [],
        ?string $dataType = self::DATA_BODY,
        ?array $headers = null
    ): ResponseInterface {
        return $this->request('POST', $uri, $data, $dataType, $headers);
    }

    public function put(
        $uri,
        array $data = [],
        ?string $dataType = self::DATA_BODY,
        ?array $headers = null
    ): ResponseInterface {
        return $this->request('PUT', $uri, $data, $dataType, $headers);
    }

    private function request(
        string $method,
        string $uri,
        ?array $data = null,
        ?string $dataType = self::DATA_BODY,
        ?array $headers = null
    ): ResponseInterface {
        $options = [];

        if ($headers !== null) {
            $options['headers'] = $headers;
        }

        if ($data !== null && in_array($method, ['GET', 'DELETE'])) {
            $options['query'] = $data;
        } elseif ($data !== null && in_array($method, ['POST', 'PUT'])) {
            $options[$dataType] = $data;
        }

        return $this->client->request($method, $uri, $options);
    }
}
