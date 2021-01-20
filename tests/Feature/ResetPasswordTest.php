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


    public function test_a_token_can_be_validated(){

        $this->seed();
        $user = User::find(1); 

        $user->token = strval(rand(1000,9999));
        $user->save();

        $response = $this->postjson('/api/auth/token/validate',[
            "otp" => $user->token,
        ]);
        
        $response->assertStatus(200);
       
    }

    public function test_a_user_can_reset_password(){
        $this->seed();
        $user = User::find(1); 

        $user->token = rand(1000,9999);
        $user->save();

        $response = $this->postjson('/api/auth/password/reset/'.$user->token,[
            "new_password"  => "password1234",
            "new_password_confirmation" => "password1234"
        ]);

        $response->assertStatus(200);
       
    }

    
}
