<?php

declare(strict_types=1);

namespace App\Application\Actions\Transaction;

use App\Application\Actions\Action;
use App\Infrastructure\Validate\ValidateException;
use App\Domain\Transaction\Services\TransferPayerService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;

class TransferAction extends Action
{
    public function __construct(
        LoggerInterface $logger,
        private readonly TransferPayerService $transferPayerService,
    ) {
        $this->logger = $logger;
    }

    public function payerToPayee(Request $request, Response $response, array $args): Response
    {
        $this->loadRequest($request, $response, $args);

        // Post data
        $params = $request->getParsedBody();

        // Transfer Service
        $transferValid = $this->transferPayerService->payerToPayee(
            (int) $params['payer'],
            (int) $params['payee'],
            (float) $params['value']
        );

        if ($transferValid !== true) {
            throw new ValidateException([], 'A transferência não pode ser realizada');
        }

        return $this->respondWithData(
            [],
            'Transferência realizada com sucesso',
            200
        );
    }
}
