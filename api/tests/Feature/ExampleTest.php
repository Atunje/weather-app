<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        User::factory(20)->create();

        $response = $this->get('/');

        $response->assertOk()->assertJson(
            fn (AssertableJson $json) => 
                $json->has('users', 20)
                     ->has('users.0', fn (AssertableJson $json) => 
                        $json->hasAll(['id', 'name', 'email', 'email_verified_at', 'latitude', 'longitude', 'created_at', 'updated_at'])
                            ->has('weather', fn (AssertableJson $json) => 
<<<<<<< HEAD
                                $json->hasAll(['humidity', 'pressure', 'temperature', 'windSpeed', 'forecast', 'forecastDescription'])
=======
                                $json->hasAll(['humidity', 'pressure', 'temperature', 'windSpeed'])
>>>>>>> 431ebc6230a8e7a258984360cf94a6ef2ad09412
                                ->etc()
                            )->etc()
                        )->etc()
        );
    }

    public function test_database_works()
    {
        User::factory(20)->create();

        $this->assertEquals(20, User::all()->count());
    }
}
