<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
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
            "email"=> "jane_doe@moove.com",
            "password_confirmation"=> "password",
            "first_name"=> "Jane",
            "last_name"=> "Doe"
        ]);
    }

    public function test_reset_email_can_be_sent()
    {   
        $this->register();
            
        $response = $this->postjson('/api/auth/password/email',[
            "email" => "jane_doe@moove.com",
        ]);

        $response->assertStatus(200);
    }

    public function test_email_must_be_registered(){
        $this->register();
            
        $response = $this->postjson('/api/auth/password/email',[
            "email" => "janedoe@moove.com",
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Please enter your registered email address',
        ]);
    }

    public function test_email_can_be_resent(){
        $this->register();
            
        $response = $this->postjson('/api/auth/resend/jane_doe@moove.com');

        $response->assertStatus(200);
    }


 
}
