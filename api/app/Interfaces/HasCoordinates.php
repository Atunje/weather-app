<?php

namespace App\Interfaces;

use App\DTOs\Coordinate;
use Illuminate\Database\Eloquent\Collection;

interface HasCoordinates 
{
    public function getCoordinate(): Coordinate;

    public function getUniqueId(): mixed;
}
