<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use App\DTOs\Coordinate;
use App\Enums\MeasurementUnit;
use App\Services\WeatherService;
use App\Factories\WeatherFactory;

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

        if (count($promises) > 0) {
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

    private function sendWeatherRequest(Coordinate $coordinate): \GuzzleHttp\Promise\PromiseInterface
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

        foreach ($this->entities as $entity) {
            $entity_id = $entity->getUniqueId();

            if (isset($responses[$entity_id])) {
                $res = $responses[$entity_id]['value'];
                $weatherInfo = json_decode($res->getBody(), true);

                $this->setWeatherOnEntity($entity, $weatherInfo, $this->units);
            }
        }
    }
}
