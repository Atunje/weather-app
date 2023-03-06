<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use App\DTOs\Coordinate;
use App\Enums\MeasurementUnit;
use App\Services\WeatherService;
use App\Factories\WeatherFactory;
use Symfony\Component\HttpFoundation\Response;

class OpenWeatherMap extends WeatherService
{
    private string $appId;
    private string $url;
    private Client $requestClient;

    public function __construct(WeatherFactory $weatherFactory, Client $requestClient)
    {
        parent::__construct($weatherFactory);
        $this->appId = config('weather.openweathermap.app_id');
        $this->url = config('weather.openweathermap.url');
        $this->requestClient = $requestClient;
    }

    protected function setWeatherOnEntities(): void
    {
        $promises = [];

        foreach ($this->entities as $entity) {
            if (! $this->setWeatherOnEntityFromCache($entity)) {
                $coordinate = $entity->getCoordinate();
                $promises[$entity->getUniqueId()] = $this->requestClient->getAsync($this->url, $this->requestParams($coordinate));
            }
        }

        if (count($promises) > 0) {
            $this->setWeatherFromPromises($promises);
        }
    }

    private function requestParams(Coordinate $coordinate): array
    {
        return [
            "query" => [
                'lat' => $coordinate->latitude,
                'lon' => $coordinate->longitude,
                'appid' => $this->appId,
            ]
        ];
    }

    private function setWeatherFromPromises(array $promises): void
    {
        $responses = Promise\Utils::settle($promises)->wait();

        foreach ($this->entities as $entity) {
            $entity_id = $entity->getUniqueId();

            if (isset($responses[$entity_id])) {
                $res = $responses[$entity_id]['value'];
                $weatherInfo = json_decode($res->getBody(), true);

                $this->setWeatherOnEntity($entity, $weatherInfo);
            }
        }
    }

    public function updateWeatherInCache(Coordinate $coordinate): void
    {
        $res = $this->requestClient->request('GET', $this->url, $this->requestParams($coordinate));
        
        if ($res->getStatusCode() == Response::HTTP_OK) {
            $weatherInfo = json_decode($res->getBody(), true);

            $weather = $this->createWeather($weatherInfo);
            $this->saveWeatherInCache($coordinate, $weather);
        }
    }
}
