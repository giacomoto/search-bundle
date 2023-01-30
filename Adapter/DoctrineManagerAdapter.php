<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Adapter;

use Luckyseven\Bundle\LuckysevenSearchBundle\Adapter\Interface\IOrmManagerAdapter;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

final class DoctrineManagerAdapter implements IOrmManagerAdapter
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $entityClass
     * @return string
     */
    public function getTableName(string $entityClass): string
    {
        return $this->entityManager->getClassMetadata($entityClass)->getTableName();
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @param ResultSetMapping|null $rsm
     * @return array
     * @throws Exception
     */
    public function executeQuery(string $sql, ?array $params = [], ?ResultSetMapping $rsm = null): array
    {
        if ($rsm) {
            $query = $this->entityManager
                ->createNativeQuery($sql, $rsm)
                ->setParameters($params);

            return array_map(static function ($result) {
                $result[0]->setDistance($result['distance']);
                return $result[0];
            }, $query->getResult());
        } else {
            $stm = $this->entityManager
                ->getConnection()
                ->prepare($sql);

            foreach ($params as $key => $val) {
                $stm->bindParam($key, $val);
            }

            return $stm
                ->execute()
                ->fetchAllAssociative();
        }
    }

    /**
     * @param string $entityClass
     * @param string $tableAlias
     * @return ResultSetMapping
     */
    public function mapResults(string $entityClass, string $tableAlias): ResultSetMapping
    {
        $rsm = new ResultSetMappingBuilder($this->entityManager);
        $rsm->addRootEntityFromClassMetadata($entityClass, $tableAlias);
        $rsm->addScalarResult('distance', 'distance');
        return $rsm;
    }
}
