<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Trip;
use Database\Seeders\TripSeeder;
use Database\Seeders\UserSeeder;

class TripTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_a_rider_can_be_found()
    {
        $this->seed(TripSeeder::class);

        $customer = User::customer()->first();

        $response = $this->actingAs($customer)->postjson('/api/request-rider', [
            "recipient_name" => "Susan James",
            "recipient_phone_number" => "0787998476",
            "package_description" => "I am moving a shoe",
            "start_location" => " Somewhere in surulere Lagos",
            "end_location" => " 3 Somewhere in a location in Ajah",
            "payment_method" => "CASH",
            "latitude" => 5.503239,
            "longitude" => 7.498161
        ]);

        $response->assertStatus(200);
    }

    public function test_a_trip_can_be_started()
    {
        $this->seed(TripSeeder::class);

        $response = $this->post('/api/start-trip/1');

        $response->assertStatus(200);
    }

    public function test_a_trip_can_be_ended()
    {
        $this->seed();

        $response = $this->post('/api/end-trip/1/1');

        $response->assertStatus(200);
    }

    public function test_a_trip_can_be_cancelled()
    {
        $this->seed();

        $response = $this->post('/api/cancel-trip/1/1');

        $response->assertStatus(200);
    }

    public function test_trip_cost_can_be_calculated()
    {
        $response = $this->postjson('/api/cost',[
            "package_description" => "I am moving a shoe",
            "start_location" => " Somewhere in surulere Lagos",
            "end_location" => " 3 Somewhere in a location in Ajah",
            "km" => "5.345",
            "time" => "2.566"
        ]);

        $response->assertStatus(200);
    }

    public function test_riders_active_trip_can_be_found()
    {   
        $this->seed();

        $rider = User::rider()->first();
        $response = $this->actingAs($rider)->get('/api/active-ride');

        $response->dump();
        $response->assertStatus(200);
    }

    
}
