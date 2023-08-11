<?php

namespace App\Domain\User\Repositories;

use App\Infrastructure\Persistence\Models\User;

interface UserRepository
{
    public function get(int $id);

    public function add(string $fullName, string $cpfCnpj, string $email, string $password, string $type): User | null;
}
