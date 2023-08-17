<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositories\Transaction;

use App\Domain\Transaction\Repositories\BankAccountRepository as BankAccountRepositoryInterface;
use App\Domain\Transaction\Models\BankAccount;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Parameter;

class BankAccountRepository extends EntityRepository implements BankAccountRepositoryInterface
{
    public function getByUserId(int $userId)
    {
        return $this->findOneBy(['userId' => $userId]);
    }

    public function decreaseBalance(int $userId, float $amount)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->update(BankAccount::class, 'b')
            ->set('b.balance', 'b.balance - :balance')
            ->where('b.userId = :userId')
            ->setParameters(new ArrayCollection([
                new Parameter('balance', $amount),
                new Parameter('userId', $userId)
            ]))
            ->getQuery()
            ->execute();

        return $result;
    }

    public function increaseBalance(int $userId, float $amount)
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
            ->update(BankAccount::class, 'b')
            ->set('b.balance', 'b.balance + :balance')
            ->where('b.userId = :userId')
            ->setParameters(new ArrayCollection([
                new Parameter('balance', $amount),
                new Parameter('userId', $userId)
            ]))
            ->getQuery()
            ->execute();

        return $result;
    }

    public function add(int $userId, float $amount)
    {
        $bankAccount = new BankAccount();
        $bankAccount->fill(['user_id' => $userId, 'balance' => $amount]);

        $this->getEntityManager()->persist($bankAccount);
        $this->getEntityManager()->flush();

        return $bankAccount;
    }
}
