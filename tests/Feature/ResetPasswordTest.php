<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    const VALIDATE_URL = '/api/auth/token/validate';

    protected function setUp(): void{
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->user = User::first();   
    }

    public function test_a_token_can_be_validated(){
        
        $response = $this->postjson(self::VALIDATE_URL,[
            "token" => $this->user->token,
            "email" => $this->user->email
        ]);
        
        $response->assertStatus(200);
       
    }

    public function test_that_I_can_only_use_my_token(){
        
        $response = $this->postjson(self::VALIDATE_URL,[
            "token" => $this->user->token,
            "email" => User::skip(1)->first()->email
        ]);
        
        $response->assertStatus(422);
       
    }

    public function test_that_I_must_pass_email_to_validate_token(){

        $response = $this->postjson(self::VALIDATE_URL,[
            "token" => $this->user->token,
        ]);
        
        $response->assertStatus(422);
       
    }

    public function test_a_user_can_reset_password_and_login(){

        $password =  "password1234";
        $validate = $this->postjson('/api/auth/password/reset',[
            "token" => $this->user->token,
            "email" => $this->user->email,
            "password"  => $password,
            "password_confirmation" => $password
        ]);

        
        $response = $this->postjson('/api/auth/login', [
            "email"  => $this->user->email,
            "password" => $password
        ]);

        $validate->assertStatus(200);
        $response->assertStatus(200);
       
    }

    public function test_a_user_should_not_login_after_reset(){

        $password = "passwod11234";
        $reset = $this->postjson('/api/auth/password/reset', [
            "password"  => $password,
            "password_confirmation" => $password,
            "token" => $this->user->token,
            "email" => $this->user->email
        ]);


        $response = $this->postjson('/api/auth/login', [
            "email"  => $this->user->email,
            "password" => "password12341"
        ]);

        $reset->assertStatus(200);
        $response->assertStatus(400);
       
    }

    
}
