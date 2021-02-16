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

    protected $customer;
    protected $rider;

    protected function setUp(): void{
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(TripSeeder::class);
        $this->customer = User::customer()->first();
        $this->rider = User::rider()->first();
    }

    public function test_a_rider_can_be_found()
    {

        $response = $this->actingAs($this->customer)->postjson('/api/request-rider', [
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

    public function test_customers_order_history_can_be_gotten()
    {   
        $response = $this->actingAs($this->customer)->get('/api/customer-history');

        $response->assertStatus(200);
    
    }

}
