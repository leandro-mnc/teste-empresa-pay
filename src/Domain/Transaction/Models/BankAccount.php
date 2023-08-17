<?php

declare(strict_types=1);

namespace App\Domain\Transaction\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use App\Infrastructure\Persistence\Doctrine\Model;
use App\Infrastructure\Persistence\Doctrine\Repositories\Transaction\BankAccountRepository;

#[Entity(repositoryClass: BankAccountRepository::class), Table(name: 'bank_account')]
final class BankAccount extends Model
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'integer', name: 'user_id', nullable: false)]
    private int $userId;

    #[Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private float $balance;

    #[Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function setBalance(float $balance)
    {
        $this->balance = $balance;
        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'balance' => $this->getBalance()
        ];
    }
}
