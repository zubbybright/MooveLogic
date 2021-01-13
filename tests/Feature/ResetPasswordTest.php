<?php

namespace Tests\Feature;

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

    private $token = '4784';

    private function saveUser(){
        $user = new User;
        $user->phone_number = "11122211111";
        $user->email =  "jane_doe@moove.com";
        $user->user_type = 'CUSTOMER';
        $user->password = \bcrypt('password');
        $user->token = $this->token;
        $user->save();
    }


    public function test_a_token_can_be_validated(){
        $this->saveUser();
        // echo $this->token;
        // die();

        $response = $this->postjson('/api/auth/token/validate',[
            "otp" => $this->token,
        ]);

        $response->dump();
        $response->assertStatus(200);
       
    }

    public function test_a_user_can_reset_password(){
        $this->saveUser();
        // echo $this->token;
        // die();

        $response = $this->postjson('/api/auth/password/reset/4784',[
            "new_password"  => "password1234",
            "new_password_confirmation" => "password1234"
        ]);

        $response->dump();
        $response->assertStatus(200);
       
    }

    
}
