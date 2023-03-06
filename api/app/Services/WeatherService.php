<?php

namespace App\Services;

use App\DTOs\Weather;
use App\DTOs\Coordinate;
use App\Jobs\UpdateWeatherJob;
use App\Enums\MeasurementUnit;
use App\Factories\WeatherFactory;
use App\Interfaces\HasCoordinates;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

abstract class WeatherService
{
    /**
     * Age of cached weather info
     */
    protected int $cacheAge;

    /**
     * Collection $entities
     *
     * Entities upon which weather info are to be added
     */
    protected Collection $entities;

    /**
     * Units of measurement
     */
    protected MeasurementUnit $units = MeasurementUnit::Imperial;

    /**
     * Constructor
     */
    public function __construct(protected readonly WeatherFactory $weatherFactory)
    {
        $this->cacheAge = config('weather.cache_age');
    }

    /**
     * setEntities
     *
     * Set the supplied entities in the instance
     */
    private function setEntities(Collection $entities): void
    {
        $this->entities = $entities;
    }

    /**
     * updateEntities
     *
     * update entities with weather information
     */
    public function updateEntities(Collection $entities): Collection
    {
        $this->setEntities($entities);

        $this->setWeatherOnEntities();

        return $this->entities;
    }

    /**
     * setWeatherOnEntityFromCache
     *
     * Sets the entity weather if available in cache
     */
    protected function setWeatherOnEntityFromCache(HasCoordinates $entity): bool
    {
        $coordinate = $entity->getCoordinate();
        $weather = Cache::get($coordinate->toString(), null);

        if ($weather != null) {
            $entity->setWeather($weather);
            
            //update the weather in the cache
            UpdateWeatherJob::dispatch($coordinate);

            return true;
        } 

        return false;
    }

    /**
     * createWeather
     *
     * Create a weather instance
     */
    protected function setWeatherOnEntity(HasCoordinates $entity, array $weatherInfo): void
    {
        $weather = $this->createWeather($weatherInfo);
        $entity->setWeather($weather);

        $this->saveWeatherInCache($entity->getCoordinate(), $weather);
    }

    /**
     * createWeather
     * 
     * Use the WeatherFactory instance to create weather
     */
    protected function createWeather(array $weatherInfo): Weather
    {
        return $this->weatherFactory->create($weatherInfo, $this->units);
    }

    protected function saveWeatherInCache(Coordinate $coordinate, Weather $weather): void
    {
        Cache::put($coordinate->toString(), $weather, $this->cacheAge);
    }

    /**
     * setWeatherOnEntities
     *
     * Update individual entity with weather information
     */
    abstract protected function setWeatherOnEntities(): void;

    /**
     * updateWeatherInCache
     * 
     * request for weather and update the cache
     */
    abstract public function updateWeatherInCache(Coordinate $coordinate): void;
}
