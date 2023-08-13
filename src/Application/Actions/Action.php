<?php

declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected LoggerInterface $logger;

    private Request $request;

    private Response $response;

    private array $args;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function loadRequest(Request $request, Response $response, array $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    /**
     * @return array|object
     */
    protected function getFormData()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * Response Error
     *
     * @param string $type
     * @param string $message
     * @param array $errors
     * @param int $statusCode
     * @return Response
     */
    protected function respondWithError(
        string $type,
        string $message,
        array $errors = null,
        int $statusCode = 402
    ): Response {
        $error = new ActionError($type, $message, $errors);

        $payload = new ActionPayload($message, null, $statusCode, $error);

        return $this->respond($payload);
    }

    /**
     * @param array|object|null $data
     */
    protected function respondWithData($data = null, string $message = '', int $statusCode = 200): Response
    {
        $payload = new ActionPayload($message, $data, $statusCode);

        return $this->respond($payload);
    }

    protected function respond(ActionPayload $payload): Response
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus($payload->getStatusCode());
    }
}
