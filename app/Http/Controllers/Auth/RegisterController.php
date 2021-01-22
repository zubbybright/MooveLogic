<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;


class RegisterController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:14','unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   
        $user = User::create([
            'phone_number' => $data['phone_number'],
            'user_type' => 0,
            'email' => $data['email'],
            'password' => $data['password'],
        ]);


        Profile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
        
        // $email =  $data['email'];
        // // $link = "moovelogic://verify";
        // Mail::send(new VerifyEmail($email,$token));

        return $user;
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
