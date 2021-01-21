<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProfileSeeder;
use App\Models\User;

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
        $this->seed();

        $user = new User; 
        $user->email = 'janedoe@email.com';
        $user->phone_number = '299292999992';
        $user->id = 11;
        $user->user_type = 'CUSTOMER';
        $user->on_a_ride = 0;
        $user->password='password';
        $user->save();
        
    $response = $this->actingAs($user)->postjson('/api/request-rider',[
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
        $response->dump();
        $response->assertStatus(200);
    }
}
