<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\WeatherService;
use App\DTOs\Coordinate;
use App\Vendors\OpenWeatherMap;

/** @mixin User */
class UserResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "weather" => $this->weather,
        ];
    }
}


