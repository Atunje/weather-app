<?php

namespace App\Jobs;

use App\DTOs\Coordinate;
use Illuminate\Bus\Queueable;
use App\Services\WeatherService;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateWeatherJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Coordinate $coordinate)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(WeatherService $weatherService): void
    {
        $weatherService->updateWeatherInCache($this->coordinate);
    }

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return $this->coordinate->toString();
    }
}
