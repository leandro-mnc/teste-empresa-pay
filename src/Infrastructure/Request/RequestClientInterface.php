<?php

namespace App\Infrastructure\Request;

interface RequestClientInterface
{
    public function get($url, ?array $params = null, ?array $headers = null);

    public function post($url, array $data = [], string $dataType = 'params', ?array $headers = null);

    public function put($url, array $data = [], string $dataType = 'params', ?array $headers = null);

    public function delete($url, ?array $params = null, ?array $headers = null);
}
