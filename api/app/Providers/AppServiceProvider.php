<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WeatherService;
use App\Services\OpenWeatherMap;
use App\Factories\WeatherFactory;
use App\Factories\OpenWeatherMapFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WeatherService::class, OpenWeatherMap::class);
        $this->app->bind(WeatherFactory::class, OpenWeatherMapFactory::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
