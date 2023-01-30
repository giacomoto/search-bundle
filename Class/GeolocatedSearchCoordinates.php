<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Class;

final class GeolocatedSearchCoordinates
{
    /**
     * @param float $latitude
     * @param float $longitude
     */
    public function __construct(
        protected float $latitude,
        protected float $longitude,
    )
    {
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }
}
