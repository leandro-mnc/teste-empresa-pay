<?php

namespace App\Infrastructure\Persistence;

use Psr\Container\ContainerInterface;
use Illuminate\Database\Connection;

class Database
{
    private Connection $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get('db')->getConnection();
    }

    public function beginTransaction()
    {
        $this->connection->getPdo()->beginTransaction();
    }

    public function commit()
    {
        $this->connection->getPdo()->commit();
    }

    public function rollBack()
    {
        $this->connection->getPdo()->rollBack();
    }
}
