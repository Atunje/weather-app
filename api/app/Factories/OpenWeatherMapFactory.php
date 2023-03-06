<?php

namespace App\Factories;

use App\DTOs\Weather;
use App\Enums\MeasurementUnit;
use Illuminate\Support\Fluent;

class OpenWeatherMapFactory extends WeatherFactory
{
    public function createWeather(array $weatherInfo): Weather
    {
        $result = new Fluent($weatherInfo);

        if (isset($result->main['humidity'])) $this->setHumidity($result->main['humidity']);
        if (isset($result->main['pressure'])) $this->setPressure($result->main['pressure'], 'hPa');
        if (isset($result->main['temp'])) $this->setTemperature($result->main['temp'], $this->getTemperatureUnit());
        if (isset($result->wind['speed'])) $this->setWindSpeed($result->wind['speed'], $this->getWindSpeedUnit());
        if (isset($result->wind['deg'])) $this->setWindDirection($result->wind['deg'], "°");
        if (isset($result->weather[0]['main'])) $this->setForecast($result->weather[0]['main']);
        if (isset($result->weather[0]['description'])) $this->setForecastDescription($result->weather[0]['description']);
        if (isset($result->weather[0]['icon'])) $this->getIconUrl($result->weather[0]['icon']);

        return $this->weather;
    }

    private function getIconUrl(string $name): string
    {
        return "http://openweathermap.org/img/wn/" . $name . ".png";
    }

    private function getTemperatureUnit(): string
    {
        return match ($this->units) {
            MeasurementUnit::Standard => 'K',
            MeasurementUnit::Imperial => '°C',
            MeasurementUnit::Metric => '°F',
        };
    }

    private function getWindSpeedUnit(): string
    {
        return match ($this->units) {
            MeasurementUnit::Standard, MeasurementUnit::Imperial => 'm/s',
            MeasurementUnit::Metric => 'mph',
        };
    }
}
