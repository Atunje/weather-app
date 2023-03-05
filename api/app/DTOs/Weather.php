<?php

namespace App\DTOs;

class Weather
{
    public string $humidity;

    public string $pressure;

    public string $temperature;

    public string $windSpeed;

    public string $windDirection;

    public string $forecast;

    public string $forecastDescription;

    public string $icon;

    public function toArray(): array
    {
        return (array) $this;
    }
}
