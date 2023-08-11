<?php

declare(strict_types=1);

use App\Application\Actions\Transaction\TransferAction;
use App\Application\Actions\User\UserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    $app->group('/user', function (Group $group) {
        $group->post('/signup', [UserAction::class, 'signup']);
    });

    $app->group('/transaction', function (Group $group) {
        $group->post('/transfer/payer-to-payee', [TransferAction::class, 'payerToPayee']);
    });
};
