<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Adapter\Interface;

use Doctrine\ORM\Query\ResultSetMapping;

interface IOrmManagerAdapter
{
    public function getTableName(string $entityClass): string;

    public function executeQuery(string $sql, ?array $params = []): array;

    public function mapResults(string $entityClass, string $tableAlias): ResultSetMapping;
}
