<?php

namespace App\Factories;

use App\DTOs\Weather;
use App\Enums\MeasurementUnit;

abstract class WeatherFactory
{
    protected Weather $weather;

    protected MeasurementUnit $units;

    public function __construct()
    {
        $this->weather = new Weather();
    }

    private function init(): self
    {
        $this->weather = new Weather();

        return $this;
    }

    protected function setHumidity(float $humidity)
    {
        $this->weather->humidity = $humidity . "%";

        return $this;
    }

    protected function setTemperature(float $temperature, string $unit)
    {
        $this->weather->temperature = $temperature . "" . $unit;

        return $this;
    }

    protected function setWindSpeed(float $windSpeed, string $unit)
    {
        $this->weather->windSpeed = $windSpeed . "" . $unit;

        return $this;
    }

    protected function setWindDirection(float $direction, string $unit)
    {
        $this->weather->windDirection = $direction . "" . $unit;

        return $this;
    }

    protected function setForecast(string $forecast)
    {
        $this->weather->forecast = $forecast;

        return $this;
    }

    protected function setForecastDescription(string $description)
    {
        $this->weather->forecastDescription = $description;

        return $this;
    }

    protected function setIcon(string $icon)
    {
        $this->weather->icon = $icon;

        return $this;
    }

    protected function setPressure(string $pressure, string $unit)
    {
        $this->weather->pressure = $pressure . '' . $unit;

        return $this;
    }

    private function setUnits(MeasurementUnit $units): void
    {
        $this->units = $units;
    }

    public function create(array $weatherInfo, MeasurementUnit $units): Weather
    {
        $this->setUnits($units);

        return $this->init()->createWeather($weatherInfo);
    }

    abstract public function createWeather(array $weatherInfo): Weather;
}
