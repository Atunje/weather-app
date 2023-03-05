<?php

namespace App\Interfaces;

use App\DTOs\Weather;
use App\DTOs\Coordinate;

interface HasCoordinates
{
    public function getCoordinate(): Coordinate;

    public function getUniqueId(): mixed;

    public function setWeather(Weather $weather): void;
}
