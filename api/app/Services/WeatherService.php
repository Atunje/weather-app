<?php

namespace App\Services;

use App\DTOs\Weather;
use App\DTOs\Coordinate;
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
    protected string $cacheAge;

    /**
     * Collection $entities
     * 
     * Entities upon which weather info are to be added
     */
    protected Collection $entities;

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
     * 
     * @throws NullPointerException
     */
    public function updateEntities(Collection $entities): Collection
    {
        $this->setEntities($entities);

        $this->setWeatherOnEntities();

        return $this->entities;
    }

    /**
     * setWeatherIfAvailable
     * 
     * Sets the entity weather if available in cache
     */
    protected function setWeatherIfAvailable(HasCoordinates $entity): bool
    {
        $coordinate = $entity->getCoordinate();

        $weather = Cache::get($coordinate->toString(), null);

        if ($weather != null) {
            $entity->weather = $weather;

            return true;
        }

        return false;
    }

    /**
     * createWeather
     * 
     * Create a weather instance 
     */
    protected function createWeather(array $weatherInfo, HasCoordinates $entity, ?MeasurementUnit $units = null): Weather 
    {
        $coordinate = $entity->getCoordinate();

        return Cache::remember($coordinate->toString(), $this->cacheAge, function () use ($weatherInfo, $units) {
            return $this->weatherFactory->create($weatherInfo, $units);
        });
    }

    /**
     * setWeatherOnEntities
     * 
     * Update individual entity with weather information
     */
    protected abstract function setWeatherOnEntities(): void;
}