<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Util;

use Luckyseven\Bundle\LuckysevenSearchBundle\Class\GeolocatedSearchCoordinates;
use Luckyseven\Bundle\LuckysevenSearchBundle\Enum\EGeolocatedSearchMeasureUnits;

class DistanceUtil
{
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param GeolocatedSearchCoordinates $pointA
     * @param GeolocatedSearchCoordinates $pointB
     * @param string|null $measureUnit
     * @return float Distance between points in [m] (same as earthRadius)
     * https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
     */
    public static function getDistance(GeolocatedSearchCoordinates $pointA, GeolocatedSearchCoordinates $pointB, ?string $measureUnit = EGeolocatedSearchMeasureUnits::METERS): float
    {
        $earthRadius = match ((float)$measureUnit) {
            (float)EGeolocatedSearchMeasureUnits::MILES => 3964,
            (float)EGeolocatedSearchMeasureUnits::KILOMETERS => 6379,
            default => 6379000,
        };

        // convert from degrees to radians
        $latFrom = deg2rad($pointA->getLatitude());
        $lonFrom = deg2rad($pointA->getLongitude());
        $latTo = deg2rad($pointB->getLatitude());
        $lonTo = deg2rad($pointB->getLongitude());

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}
