<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Service;

use Luckyseven\Bundle\LuckysevenSearchBundle\Adapter\Interface\IOrmManagerAdapter;
use Luckyseven\Bundle\LuckysevenSearchBundle\Class\GeolocatedSearchCoordinates;
use Luckyseven\Bundle\LuckysevenSearchBundle\Class\GeolocatedSearchOptions;
use Luckyseven\Bundle\LuckysevenSearchBundle\Trait\TGeolocatedSearchQueryStruct;
use Luckyseven\Bundle\LuckysevenSearchBundle\Util\DistanceUtil;

class SearchService
{
    use TGeolocatedSearchQueryStruct;

    public function __construct(
        protected IOrmManagerAdapter $ormAdapter,
    )
    {
    }

    /**
     * @param string $entityClass
     * @param GeolocatedSearchOptions $geolocatedSearchOptions
     * @param callable|null $queryStructCallback
     * @return array
     */
    public function searchByCoords(string $entityClass, GeolocatedSearchOptions $geolocatedSearchOptions, ?callable $queryStructCallback = null): array
    {
        $tableAlias = 'result';
        $tableName = $this->ormAdapter->getTableName($entityClass);

        $queryStruct = $this->findByCoordsQueryStruct(
            $tableName,
            $tableAlias,
            $geolocatedSearchOptions
        );

        if (isset($queryStructCallback)) {
            $queryStructCallback($queryStruct, $tableAlias);
        }

        $resultSetMapping = $this->ormAdapter->mapResults(
            $entityClass,
            $tableAlias,
        );

        $results = $this->ormAdapter->executeQuery(
            $queryStruct->getSql(),
            $queryStruct->getParameters(),
            $resultSetMapping
        );

        if ($geolocatedSearchOptions->getDepartureCoordinates()) {
            foreach ($results as $result) {
                $result->setDistance(DistanceUtil::getDistance(
                    $geolocatedSearchOptions->getDepartureCoordinates(),
                    new GeolocatedSearchCoordinates($result->getLatitude(), $result->getLongitude()),
                    $geolocatedSearchOptions->getMeasureUnit()
                ));
            }

            usort($results, static fn($a, $b) => $a->getDistance() > $b->getDistance());
        }

        return $results;
    }
}
