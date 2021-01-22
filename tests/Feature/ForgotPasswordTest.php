<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    const RESET_EMAIL_URL = '/api/auth/password/email';

    protected function setUp(): void{
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->user = User::first();   
    }


    public function test_reset_email_can_be_sent()
    {   
        $response = $this->postjson(self::RESET_EMAIL_URL,[
            "email" => $this->user->email,
        ]);

        $response->assertStatus(200);
    }

    public function test_email_must_be_registered()
    {
        $response = $this->postjson(self::RESET_EMAIL_URL,[
            "email" => "example@example.com",
        ]);

        $response->assertStatus(422);
    }

    public function test_email_can_be_resent()
    {        
        $response = $this->post('/api/auth/resend/'.$this->user->email);
        $response->assertStatus(200);
    }

}
