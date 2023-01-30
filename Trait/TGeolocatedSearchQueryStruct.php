<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Trait;

use Luckyseven\Bundle\LuckysevenSearchBundle\Class\GeolocatedSearchOptions;
use Luckyseven\Bundle\LuckysevenSearchBundle\Class\QueryStructure;

trait TGeolocatedSearchQueryStruct
{
    public function findByCoordsQueryStruct(string $tableName, string $tableAlias, GeolocatedSearchOptions $options): QueryStructure
    {
        $queryStructure = (new QueryStructure())
            ->addSelect("$tableAlias.*")
            ->addSelect("distance")
            ->setFrom("(
                    SELECT __t.*, 
                           __q.radius, 
                           __q.distance_unit
                             * DEGREES(ACOS(LEAST(1.0, COS(RADIANS(__q.latpoint))
                                                           * COS(RADIANS(__t.latitude))
                                                           * COS(RADIANS(__q.longpoint - __t.longitude))
                             + SIN(RADIANS(__q.latpoint))
                                                           * SIN(RADIANS(__t.latitude))))) AS distance
                        FROM {$tableName} AS __t
                           JOIN ( /* these are the query parameters */
                              SELECT {$options->getCoordinates()->getLatitude()}  AS latpoint,
                                     {$options->getCoordinates()->getLongitude()} AS longpoint,
                                     {$options->getRadius()}      AS radius,
                                     {$options->getMeasureUnit()} AS distance_unit) AS __q ON 1 = 1
                        WHERE __t.latitude
                            BETWEEN __q.latpoint - (__q.radius / __q.distance_unit)
                            AND __q.latpoint + (__q.radius / __q.distance_unit)
                            AND __t.longitude
                                BETWEEN __q.longpoint - (__q.radius / (__q.distance_unit * COS(RADIANS(__q.latpoint))))
                            AND __q.longpoint + (__q.radius / (__q.distance_unit * COS(RADIANS(__q.latpoint))))
                    ) as {$tableAlias}
            ")
            ->andWhere("distance <= radius")
            ->addOrderBy('distance');


        if ($options->getMaxResults()) {
            $queryStructure->setLimit($options->getMaxResults());
        }

        if ($options->getResultsOffset()) {
            $queryStructure->setOffset($options->getResultsOffset());
        }

        return $queryStructure;
    }
}
