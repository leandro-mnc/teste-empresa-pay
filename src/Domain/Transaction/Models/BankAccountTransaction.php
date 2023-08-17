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
use App\Infrastructure\Persistence\Doctrine\Repositories\Transaction\BankAccountTransactionRepository;

#[Entity(repositoryClass: BankAccountTransactionRepository::class), Table(name: 'bank_account_transaction')]
final class BankAccountTransaction extends Model
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'integer', name: 'bank_account_id', nullable: false)]
    private int $bankAccountId;

    #[Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private float $amount;

    #[Column(type: 'string', nullable: true)]
    private string $description;

    #[Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function getBankAccountId()
    {
        return $this->bankAccountId;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function setBankAccountid(int $bankAccountId)
    {
        $this->bankAccountId = $bankAccountId;
        return $this;
    }

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
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
        'bank_account_id' => $this->getBankAccountId(),
        'amount' => $this->getAmount()
        ];
    }
}
