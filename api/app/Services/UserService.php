<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(private readonly WeatherService $weatherService)
    {
    }

    public function getUsers(): mixed
    {
        $users = $this->weatherService->updateEntities($this->getAll());

        return UserResource::collection($users);
    }

    private function getAll(): Collection
    {
        $users = Cache::get('users', null);

        if ($users === null) {
            $expire = Carbon::now()->addMinutes(10);

            $users = Cache::remember('users', $expire, function () {
                return User::all();
            });
        }

        return $users;
    }
}
