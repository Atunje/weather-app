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
        $users = Cache::get('all_users', null);

        if ($users == null) {
            $users = User::all();

            if($users->count() > 0) {
                $expire = Carbon::now()->addMinutes(5);
                Cache::put('all_users', $users, $expire);
            }
        }

        return $users;
    }
}
