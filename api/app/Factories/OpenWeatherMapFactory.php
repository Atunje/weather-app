<?php

namespace App\Factories;

use App\DTOs\Weather;
use Illuminate\Support\Arr;
use App\Enums\MeasurementUnit;
use Illuminate\Support\Fluent;
use App\Exceptions\WeatherAPIException;
use App\Http\Resources\OpenWeatherMapResource;

class OpenWeatherMapFactory extends WeatherFactory
{
    public function createWeather(array $weatherInfo): Weather
    {
        try {
            $this->setHumidity(Arr::get($weatherInfo, 'main.humidity'))
                ->setPressure(Arr::get($weatherInfo, 'main.pressure'), 'hPa')
                ->setTemperature(Arr::get($weatherInfo, 'main.temp'), $this->getTemperatureUnit())
                ->setWindSpeed(Arr::get($weatherInfo, 'wind.speed'), $this->getWindSpeedUnit())
                ->setWindDirection(Arr::get($weatherInfo, 'wind.deg'), "°")
                ->setForecast(Arr::get($weatherInfo, 'weather.0.main'))
                ->setForecastDescription(Arr::get($weatherInfo, 'weather.0.descriptiond'))
                ->setIcon($this->getIconUrl(Arr::get($weatherInfo, 'weather.0.icon')));
        } catch(\Throwable $e) {
            report("Error extracting data from weather api response");
            return new Weather();
        }

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
