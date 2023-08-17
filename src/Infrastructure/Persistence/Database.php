<?php

namespace App\Infrastructure\Persistence;

use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;

class Database
{
    private EntityManagerInterface $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get(EntityManagerInterface::class);
    }

    public function beginTransaction()
    {
        $this->connection->getConnection()->beginTransaction();
    }

    public function commit()
    {
        $this->connection->getConnection()->commit();
    }

    public function rollBack()
    {
        $this->connection->getConnection()->rollBack();
    }
}
