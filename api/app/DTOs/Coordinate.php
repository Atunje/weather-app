<?php

namespace App\DTOs;

class Coordinate
{
    public function __construct(public readonly float $longitude, public readonly float $latitude)
    {
        //
    }

    public function toString()
    {
        return "[".$this->longitude . "," . $this->latitude . "]";
    }
}