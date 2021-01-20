<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     const AUTH_URL = '/api/auth/login';
     
    public function test_a_user_can_login_with_email()
    {   
        $this->seed();

        $user = User::find(1);               
        $response = $this->postjson(self::AUTH_URL,[
            "email" => $user->email,
            "password" => "password",
        ]);
        
        $response->assertStatus(200);
        
    }

    public function test_a_user_can_login_with_phone()
    {   
        $this->seed();
             
        $user = User::first();               
        $response = $this->postjson(self::AUTH_URL,[
            "phone_number" => $user->phone_number,
            "password" => "password",
        ]);
        
        $response->assertStatus(200);
        
    }

    public function test_a_user_cannot_login_with_wrong_email_format()
    {   
        $this->seed();

        $response = $this->postjson(self::AUTH_URL,[
            "email" => "email@email",
            "password" => "password",
        ]);
        
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_login_with_wrong_credential()
    {   
        $this->seed();

        $user = User::first();               
        $response = $this->postjson(self::AUTH_URL,[
            "email" => $user->email,
            "password" => "password1",
        ]);
        
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid Login Credentials.',
        ]);
        
    }
}
