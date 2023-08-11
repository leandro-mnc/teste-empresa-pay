<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\User\Repositories\UserRepository as UserRepositoryInterface;
use App\Infrastructure\Persistence\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function get(int $id)
    {
        return User::find($id);
    }

    public function add(string $fullName, string $cpfCnpj, string $email, string $password, string $type): ?User
    {
        $user = new User([
            'full_name' => $fullName,
            'cpf_cnpj' => $cpfCnpj,
            'email' => $email,
            'password' => $password,
            'type' => $type
        ]);

        if ($user->save() === true) {
            return $user;
        }

        return null;
    }
}
