<?php

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\Services\UserSignupService;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserAction extends Action
{
    public function __construct(private readonly UserSignupService $signupService)
    {
    }

    public function signup(RequestInterface $request, ResponseInterface $reponse, array $args)
    {
        $this->loadRequest($request, $reponse, $args);

        $data = $this->getFormData();

        $user = $this->signupService->add($data);

        return $this->respondWithData([
            'message' => 'User added successfully',
            'user' => $user
        ], 201);
    }
}
