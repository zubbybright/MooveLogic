<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     const AUTH_URL = '/api/auth/login';
     protected $seeder = UserSeeder::class;

    public function test_a_user_can_login_with_email()
    {               
        $response = $this->postjson(self::AUTH_URL,[
            "email" => "user11@moove.com",
            "password" => "password",
        ]);
        
        $response->assertStatus(200);
        
    }

    public function test_a_user_can_login_with_phone()
    {               
        $response = $this->postjson(self::AUTH_URL,[
            "phone_number" => 11122211111,
            "password" => "password",
        ]);
        
        $response->assertStatus(200);
        
    }

    public function test_a_user_cannot_login_with_wrong_email_format()
    {   
        // $this->register();
            
        $response = $this->postjson(self::AUTH_URL,[
            "email" => 92003993993,
            "password" => "password",
        ]);
        
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_login_with_wrong_credential()
    {   
        // $this->register();
            
        $response = $this->postjson(self::AUTH_URL,[
            "email" => 'email@email.com',
            "password" => "password",
        ]);
        
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid Login Credentials.',
        ]);
        
    }
}
