<?php

namespace App\Services;

use App\Services\WeatherService;
use App\DTOs\Coordinate;
use App\DTOs\Weather;
use App\Factories\WeatherFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\Enums\MeasurementUnit;

class OpenWeatherMap extends WeatherService
{
    private string $appId;
    private string $url;
    private Client $requestClient;
    private MeasurementUnit $units = MeasurementUnit::Imperial;

    public function __construct(WeatherFactory $weatherFactory, Client $requestClient)
    {
        parent::__construct($weatherFactory);
        $this->appId = config('weather.openweathermap.app_id');
        $this->url = config('weather.openweathermap.url');
        $this->requestClient = $requestClient;
    }

    protected function setWeatherOnEntities(): void
    {
        $promises = $this->sendRequests();

        if(count($promises) > 0) {
            $this->setWeatherFromResponses($promises);
        }
    }

    private function sendRequests(): array
    {
        $promises = [];

        foreach ($this->entities as $entity) {
            if (! $this->setWeatherIfAvailable($entity)) {
                $promises[$entity->getUniqueId()] = $this->sendWeatherRequest($entity->getCoordinate());
            }
        }

        return $promises;
    }

    private function sendWeatherRequest(Coordinate $coordinate): \GuzzleHttp\Promise\Promise
    {
        return $this->requestClient->getAsync($this->url, [
            "query" => [
                'lat' => $coordinate->latitude,
                'lon' => $coordinate->longitude,
                'appid' => $this->appId,
            ],
        ]);
    }

    private function setWeatherFromResponses(array $promises): void
    {
        $responses = Promise\Utils::settle($promises)->wait();

        foreach($this->entities as $entity) {
            $entity_id = $entity->getUniqueId();
            
            if(isset($responses[$entity_id])) {
                $res = $responses[$entity_id]['value'];
                $body = json_decode($res->getBody(), true);

                $entity->weather = $this->createWeather($body, $entity, $this->units);
            }
        }
    }
}

