<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Models\User;

interface UserRepository
{
    public function get(int $id): User | null;

    public function add(string $fullName, string $cpfCnpj, string $email, string $password, string $type): User | null;
    
    public function cpfCnpjExists(string $cpfCpnpj): bool;
    
    public function emailExists(string $email): bool;
}
