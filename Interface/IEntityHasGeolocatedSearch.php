<?php

namespace Luckyseven\Bundle\LuckysevenSearchBundle\Interface;

interface IEntityHasGeolocatedSearch {
    public function getLatitude(): ?float;
    public function getLongitude(): ?float;

    public function setDistance(float $distance): self;
    public function getDistance(): ?float;
}
