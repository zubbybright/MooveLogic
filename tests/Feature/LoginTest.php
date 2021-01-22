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
     protected $user;

     protected function setUp(): void{
         parent::setUp();
         $this->seed(UserSeeder::class);
         $this->user = User::first();   
     }

     
    public function test_a_user_can_login_with_email()
    {   

                    
        $response = $this->postjson(self::AUTH_URL,[
            "email" => $this->user->email,
            "password" => "password",
        ]);
        
        $response->assertStatus(200);
        
    }

    public function test_a_user_can_login_with_phone()
    {       
        $response = $this->postjson(self::AUTH_URL,[
            "phone_number" => $this->user->phone_number,
            "password" => "password",
        ]);
        
        $response->assertStatus(200);
        
    }

    public function test_a_user_cannot_login_with_wrong_email_format()
    {   
        $this->seed(UserSeeder::class);

        $response = $this->postjson(self::AUTH_URL,[
            "email" => "email@email",
            "password" => "password",
        ]);
        
        $response->assertStatus(422);
    }

    public function test_a_user_cannot_login_with_wrong_credential()
    {             
        $response = $this->postjson(self::AUTH_URL,[
            "email" => $this->user->email,
            "password" => "password1",
        ]);
        
        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Invalid Login Credentials.',
        ]);
        
    }
}
