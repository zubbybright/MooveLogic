<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{   
    use RefreshDatabase;
    
    const AUTH_URL = '/api/auth/register';
    const INVALID_MESSAGE = 'The given data was invalid.';

    public function test_a_user_can_register(){
        $response = $this->postjson(self::AUTH_URL,[
            "phone_number"=>"22219499944",
            "password"=> "password",
            "email"=> "user11@moove.com",
            "password_confirmation"=> "password",
            "first_name"=> "User",
            "last_name"=> "Test"
        ]);
        $response->assertStatus(200);
    }

    public function test_a_user_cannot_register_with_empty_fields()
    {   
        
        $response = $this->postjson(self::AUTH_URL,[
            "phone_number"=>" ",
            "password"=> " ",
            "email"=> " ",
            "password_confirmation"=> " ",
            "first_name"=> " ",
            "last_name"=> " "
            
        ]);
    
        $response->assertStatus(422);
        $response->assertJson([
            "message"=> self::INVALID_MESSAGE,
        ]);   
        $response->assertJsonFragment([
            'first_name' => ["The first name field is required."],
            'last_name' => ["The last name field is required."],
            'phone_number' => ["The phone number field is required."],
            'password' =>["The password field is required."]
        ]);
        
    }

    public function test_phone_number_cannot_be_more_than_14_digits()
    {   
        $this->markTestSkipped( 'Let business decide this when we are live' );
        $response = $this->postjson(self::AUTH_URL,[
            'first_name' => 'User',
            'last_name'=>'Test',
            'phone_number' => '+234667388888883',
            'email' => 'user@user.com',
            'password' =>'password' ,
            'password_confirmation' => 'password'
            
        ]);
    
        $response->assertStatus(422);
        $response->assertJson(["message"=> self::INVALID_MESSAGE,]); 
        $response->assertJsonFragment([
            'phone_number' => ["The phone number may not be greater than 14 characters."]
        ]);
    }

    public function test_email_field_must_accept_a_valid_email_format()
    {   
        
        $response = $this->postjson(self::AUTH_URL,[
            'first_name' => 'John',
            'last_name'=>'Doe',
            'phone_number' => '12345678909',
            'email' => 'user.com',
            'password' =>'password' ,
            'password_confirmation' => 'password'
            
        ]);
    
        $response->assertStatus(422);
        $response->assertJson([
            "message"=> "The given data was invalid.",
        ]); 
        $response->assertJsonFragment([
            'email' => ['The email must be a valid email address.']
        ]);
    }

    public function test_firstname_and_lastname_cannot_be_numbers()
    {   
        
        $response = $this->postjson(self::AUTH_URL,[
            'first_name' => 102000300,
            'last_name'=>1009399494,
            'phone_number' => '88838383844',
            'email' => 'email@user.com',
            'password' =>'password' ,
            'password_confirmation' => 'password'
           
        ]);
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'first_name' => [ "The first name must be a string."],
                'last_name' =>  ["The last name must be a string."]
            ]
        ]);
    }

    public function test_a_user_cannot_register_with_existing_email_or_phone()
    {   
        $this->seed(UserSeeder::class);
        $user = User::first();

        $response = $this->postjson(self::AUTH_URL,[
            "phone_number"=>$user->phone_number,
            "password"=> "password",
            "email"=> $user->email,
            "password_confirmation"=> "password",
            "first_name"=> "User",
            "last_name"=> "Test"
        ]);
    
        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'phone_number' =>  ["The phone number has already been taken."],
                'email' => ["The email has already been taken."]
            ]
        ]);
       
        
    }

}
