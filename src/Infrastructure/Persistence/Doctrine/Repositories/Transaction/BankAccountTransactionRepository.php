<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositories\Transaction;

use App\Domain\Transaction\Repositories\BankAccountTransactionRepository as BankAccountTransactionRepositoryInterface;
use App\Domain\Transaction\Models\BankAccountTransaction;
use Doctrine\ORM\EntityRepository;

class BankAccountTransactionRepository extends EntityRepository implements BankAccountTransactionRepositoryInterface
{
    public function add(int $bank_account_id, float $amount, string $description)
    {
        $bankAccount = new BankAccountTransaction();
        $bankAccount->fill([
            'bankAccountId' => $bank_account_id,
            'amount' => $amount,
            'description' => $description
        ]);

        $this->getEntityManager()->persist($bankAccount);
        $this->getEntityManager()->flush();

        return $bankAccount;
    }
}
