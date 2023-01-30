<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Class;

use Luckyseven\Bundle\LuckysevenSearchBundle\Enum\EGeolocatedSearchMeasureUnits;

class GeolocatedSearchOptions
{
    /**
     * @param GeolocatedSearchCoordinates $coordinates
     * @param float $radius
     * @param int|null $maxResults
     * @param int|null $resultsOffset
     * @param GeolocatedSearchCoordinates|null $departureCoordinates
     * @param float|null $measureUnit
     */
    public function __construct(
        protected readonly GeolocatedSearchCoordinates  $coordinates,
        protected readonly float                        $radius,
        protected readonly ?int                         $maxResults = null,
        protected readonly ?int                         $resultsOffset = null,
        protected readonly ?GeolocatedSearchCoordinates $departureCoordinates = null,
        protected readonly ?float                       $measureUnit = EGeolocatedSearchMeasureUnits::METERS,
    )
    {
    }

    /**
     * @return int|null
     */
    public function getResultsOffset(): ?int
    {
        return $this->resultsOffset;
    }

    /**
     * @return GeolocatedSearchCoordinates
     */
    public function getCoordinates(): GeolocatedSearchCoordinates
    {
        return $this->coordinates;
    }

    /**
     * @return GeolocatedSearchCoordinates|null
     */
    public function getDepartureCoordinates(): GeolocatedSearchCoordinates|null
    {
        return $this->departureCoordinates;
    }

    /**
     * @return float
     */
    public function getRadius(): float
    {
        return $this->radius;
    }

    /**
     * @return int|null
     */
    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    /**
     * @return float
     */
    public function getMeasureUnit(): float
    {
        return $this->measureUnit;
    }
}
