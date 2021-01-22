<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends BaseController
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
        ]);

    }

    protected function create(array $data)
    {   
        $profile = Profile::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);

        return User::create([
            'phone_number' => $data['phone_number'],
            'user_type' => 0,
            'email' => $data['email'],
            'password' => $data['password'],
            'profile_id' => $profile->id,
        ]);


        
        // $email =  $data['email'];
        // // $link = "moovelogic://verify";
        // Mail::send(new VerifyEmail($email,$token));

    }

    protected function registered(Request $request, $user)
    {
       $data  = [
            'user' => $user,
            'profile' => $user->profile,
            'token' => [
                'access_token' => $this->guard()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL()
            ]
        ];
        return $this->sendResponse($data, 'User registered successfully.');
    }

    public function checkToken(Request $request){

        $data = $request->validate(['token' => ['required', 'string', 'min:4', 'max:4']]);

        $validToken = User::where('token',$data['token'])->first();
        
        if($validToken == null){
            return $this->sendError("Invalid token.", "Invalid token.");
        }
        
        return $this->sendResponse("Token is valid.", "Registration complete");
    }


}
