<?php

namespace App\Domain\Transaction\Repositories;

interface BankAccountRepository
{
    public function getByUserId(int $userId);

    public function decreaseBalance(int $userId, float $amount);

    public function increaseBalance(int $userId, float $amount);

    public function add(int $userId, float $amount);
}
