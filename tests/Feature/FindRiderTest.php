<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\TripSeeder;

class FindRiderTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_rider_can_be_found()
    {
        $this->seed(TripSeeder::class);

        $customer = User::customer()->first();

        $response = $this->actingAs($customer)->postjson('/api/request-rider', [
            "recipient_name" => "Susan James",
            "recipient_phone_number" => "0787998476",
            "package_description" => "I am moving a shoe",
            "start_location" => " Smomwhere in surulere Lagos",
            "end_location" => " 3 Somewhere in a location in Ajah",
            "payment_method" => "CASH",
            "latitude" => 5.503239,
            "longitude" => 7.498161
        ]);

        $response->assertStatus(200);
    }
}
