<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    private function register(){
        $this->postjson('/api/auth/register',[
            "phone_number"=>"11122211111",
            "password"=> "password",
            "email"=> "user11@moove.com",
            "password_confirmation"=> "password",
            "first_name"=> "Jane",
            "last_name"=> "Doe"
        ]);
    }

    public function test_a_user_can_login_with_email()
    {   
        $this->register();
            
        $response = $this->post('/api/auth/login',[
            "email" => "user11@moove.com",
            "password" => "password",
        ]);
        
        $response->dump();
        $response->assertStatus(200);
        
    }

    public function test_a_user_can_login_with_phone()
    {   
        $this->register();
            
        $response = $this->post('/api/auth/login',[
            "email" => 11122211111,
            "password" => "password",
        ]);
        
        $response->dump();
        $response->assertStatus(200);
        
    }

    public function test_a_user_cannot_login_with_invalid_credentials()
    {   
        $this->register();
            
        $response = $this->post('/api/auth/login',[
            "email" => 92003993993,
            "password" => "password",
        ]);
        
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid Login Credentials.',
        ]);
        
    }

}
