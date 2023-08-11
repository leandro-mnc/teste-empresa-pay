<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Transaction\Repositories\BankAccountTransactionRepository as RepositoryInterface;
use App\Infrastructure\Persistence\Models\BankAccountTransaction;

class BankAccountTransactionRepository implements RepositoryInterface
{
    public function add(int $bank_account_id, float $amout, string $description)
    {
        $model = new BankAccountTransaction([
            'bank_account_id' => $bank_account_id,
            'amount' => $amout,
            'description' => $description
        ]);

        if ($model->save() === true) {
            return $model;
        }

        return null;
    }
}
