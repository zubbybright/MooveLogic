<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    const RESET_EMAIL_URL = '/api/auth/password/email';


    public function test_reset_email_can_be_sent()
    {   
      
        $this->seed();
        
        $user = User::find(1); 
        $response = $this->postjson(self::RESET_EMAIL_URL,[
            "email" => $user->email,
        ]);

        $response->assertStatus(200);
    }

    public function test_email_must_be_registered(){
        $this->seed();
        
        $response = $this->postjson(self::RESET_EMAIL_URL,[
            "email" => "janedoe@moove.com",
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'message' => 'Please enter your registered email address',
        ]);
    }

    public function test_email_can_be_resent(){
        $this->seed();
        $user = User::find(1); 
        
        $response = $this->post('/api/auth/resend/'.$user->email);
        $response->assertStatus(200);
    }


 
}
