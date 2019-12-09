<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RiderController extends BaseController
{
    //
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
        $this->middleware('guest');
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
            'user_type' => [ 'string', 'max:255'],
            'facebook_id' => ['nullable','string', 'max:255'],
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
            'user_type' => 'RIDER',
            'facebook_id' => $data['facebook_id'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);


        $profile = Profile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name']
         ]);

        return $user;
    }

    protected function registered(Request $request, $user)
    {
       $data  = [
            'user' => $user,
            'token' => [
                'access_token' => $this->guard()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ];
        return $this->sendResponse($data, 'Rider registered successfully.');
    }

    
}
