<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\DTOs\Coordinate;
use App\Interfaces\HasCoordinates;
use App\DTOs\Weather;

class User extends Authenticatable implements HasCoordinates
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public Weather $weather;

    public function getCoordinate(): Coordinate 
    {
        return new Coordinate($this->longitude, $this->latitude);
    }

    public function getUniqueId(): mixed 
    {
        return $this->id;
    }

    public function setWeather(Weather $weather): void 
    {
        $this->weather = $weather;
    }
}
