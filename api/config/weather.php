<?php

return [
    
    'cache_age' => env('WEATHER_CACHE_AGE', 30),

    'openweathermap' => [

        'app_id' => env('OPENWEATHERMAP_APPID'),

        'url' => env('OPENWEATHERMAP_URL'),

    ]
];