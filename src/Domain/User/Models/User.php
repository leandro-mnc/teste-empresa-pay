<?php

namespace App\Domain\User\Models;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use App\Infrastructure\Persistence\Doctrine\Model;
use App\Infrastructure\Persistence\Doctrine\Repositories\User\UserRepository;

#[Entity(repositoryClass: UserRepository::class), Table(name: 'user')]
final class User extends Model
{
    protected bool $timestamp = true;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', name: 'full_name', length: 100, nullable: false)]
    private string $fullName;

    #[Column(type: 'string', unique: true, name: 'cpf_cnpj', length: 14, nullable: false)]
    private string $cpfCnpj;

    #[Column(type: 'string', unique: true, length: 100, nullable: false)]
    private string $email;

    #[Column(type: 'string', length: 100, nullable: false)]
    private string $password;

    #[Column(type: 'string', length: 1, nullable: false)]
    private string $type;

    #[Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[Column(name: 'updated_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getCpfCnpj(): string
    {
        return $this->cpfCnpj;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getType(): string
    {
        return $this->type === 'F' ? 'Fisíca' : 'Jurídica';
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function setFullName(string $fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function setCpfCnpj(string $cpfCnpj)
    {
        $this->cpfCnpj = $cpfCnpj;
        return $this;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    public function setType(string $type)
    {
        $this->type = $type === 'juridica' ? 'J' : 'F';
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
            'fullName' => $this->getFullName(),
            'cpfCnpj' => $this->getCpfCnpj(),
            'email' => $this->getEmail(),
            'type' => $this->getType(),
        ];
    }
}
