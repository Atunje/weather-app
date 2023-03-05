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

        $this->setHumidity($result->main['humidity'])
            ->setPressure($result->main['pressure'], 'hPa')
            ->setTemperature($result->main['temp'], $this->getTemperatureUnit())
            ->setWindSpeed($result->wind['speed'], $this->getWindSpeedUnit())
            ->setWindDirection($result->wind['deg'], "°")
            ->setForecast($result->weather[0]['main'])
            ->setForecastDescription($result->weather[0]['description'])
            ->setIcon($this->getIconUrl($result->weather[0]['icon']));

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
