<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ResetPasswordTest extends TestCase
{   
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected $user;

    protected function setUp(): void{
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->user = User::first();   
    }

    public function test_a_token_can_be_validated(){

        $response = $this->postjson('/api/auth/token/validate',[
            "otp" => $this->user->token,
        ]);
        
        $response->assertStatus(200);
       
    }

    public function test_an_invalid_toke_fails(){

        $response = $this->postjson('/api/auth/token/validate',[
            "otp" => "4566",
        ]);
        
        $response->assertStatus(422);
       
    }

    public function test_a_user_can_reset_password_and_login(){

        $password =  "password1234";
        $this->postjson('/api/auth/password/reset/'.$this->user->token,[
            "new_password"  => $password,
            "new_password_confirmation" => $password
        ]);


        $response = $this->postjson('/api/auth/login', [
            "email"  => $this->user->email,
            "password" => $password
        ]);


        $response->assertStatus(200);
       
    }

    public function test_a_user_should_not_login_after_reset(){

        $password = "passwod11234";
        $this->postjson('/api/auth/password/reset/'.$this->user->token,[
            "new_password"  => $password,
            "new_password_confirmation" => $password
        ]);


        $response = $this->postjson('/api/auth/login', [
            "email"  => $this->user->email,
            "password" => "password12341"
        ]);


        $response->assertStatus(400);
       
    }

    
}
