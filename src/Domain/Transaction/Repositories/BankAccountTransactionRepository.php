<?php

namespace App\Domain\Transaction\Repositories;

interface BankAccountTransactionRepository
{
    public function add(int $bank_account_id, float $amout, string $description);
}
