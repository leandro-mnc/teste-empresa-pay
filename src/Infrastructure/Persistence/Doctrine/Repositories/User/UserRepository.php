<?php

namespace App\Infrastructure\Persistence\Doctrine\Repositories\User;

use Doctrine\ORM\EntityRepository;
use App\Domain\User\Repositories\UserRepository as UserRepositoryInterface;
use App\Domain\User\Models\User;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    public function add(string $fullName, string $cpfCnpj, string $email, string $password, string $type): ?User
    {
        $user = new User();
        $user->fill([
            'fullName' => $fullName,
            'cpfCnpj' => $cpfCnpj,
            'email' => $email,
            'password' => $password,
            'type' => $type,
        ]);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    public function get(int $id): ?User
    {
        return $this->find($id);
    }

    public function cpfCnpjExists(string $cpfCpnpj): bool
    {
        return $this->count(['cpfCnpj' => $cpfCpnpj]) > 0;
    }

    public function emailExists(string $email): bool
    {
        return $this->count(['email' => $email]) > 0;
    }
}
