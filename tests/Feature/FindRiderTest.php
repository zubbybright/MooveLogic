<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UsersTableSeeder;
use ProfilesTableSeeder;

class FindRiderTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_rider_can_be_found()
    {   
        $user = $this->seed(UsersTableSeeder::class);
        $profile = $this->seed(ProfilesTableSeeder::class);

    $response = $this->actingAs($user, 'api')->postjson('/api/request-rider',[
        "recipient_name"=> "Susan James",
        "recipient_phone_number" => "0787998476",
        "package_description" => "I am moving a shoe",
        "who_pays" => "RECIPIENT",
        "start_location" => " Smomwhere in surulere Lagos",
        "end_location" => " 3 Somewhere in a location in Ajah",
        "payment_method"=> "CASH",
        "latitude"=> 5.503239,
        "longitude"=> 7.498161
    ]);

        $response->assertStatus(200);
    }
}
