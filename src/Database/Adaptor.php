<?php

namespace App\Database;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class Adaptor
{
    private $connection;

    private $entityManager;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->connection    = $connection;
        $this->entityManager = $entityManager;
    }

    public function syncShipments()
    {
        $this->callProcedure('call sync_shipments()');
    }
    public function archiveShipments()
    {
        $this->callAndReturnFunction('archive_shipments()');
    }

    public function archiveOrders()
    {
        $this->callAndReturnFunction('archive_orders()');
    }

    private function callProcedure(string $call)
    {
        $stmt = $this->connection->prepare($call);
        $stmt->execute();
    }

    private function callAndReturnFunction(string $call, array $parameters = [])
    {
        $mapping = (new ResultSetMapping())->addScalarResult('result', 'result');
        $sql = 'select ' . $call . ' as result';
        $query = $this->entityManager->createNativeQuery($sql, $mapping);
        if ($parameters) {
            $query->setParameters($parameters);
        }

        return $query->getSingleScalarResult();
    }
}