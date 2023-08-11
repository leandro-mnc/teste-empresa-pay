<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Transaction\Repositories\BankAccountRepository as RepositoryInterface;
use App\Infrastructure\Persistence\Models\BankAccount;
use Illuminate\Database\Capsule\Manager as DB;

class BankAccountRepository implements RepositoryInterface
{
    public function getByUserId(int $userId)
    {
        return BankAccount::where('user_id', $userId)->first();
    }

    public function increaseBalance(int $userId, float $amount)
    {
        return BankAccount::where('user_id', $userId)->update(['balance' => DB::raw('balance + ' . $amount)]);
    }

    public function decreaseBalance(int $userId, float $amount)
    {
        return BankAccount::where('user_id', $userId)->update(['balance' => DB::raw('balance - ' . $amount)]);
    }

    public function add(int $userId, float $balance)
    {
        $model = $this->getByUserId($userId);

        if ($model === null) {
            $model = new BankAccount([
                'user_id' => $userId,
                'balance' => $balance
            ]);
        }

        $model->save();

        return $model;
    }
}
