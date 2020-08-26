<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends BaseController
{

    /**
     * Create a new Authcontroller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $input = $request->all();
  
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            
        ]);

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';

        if($token = auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password']))){
            
            $data  = [
                'user' => auth()->user(),
                'profile' => auth()->user()->profile,
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL()
                ]
            ];
    
            return $this->sendResponse($data, 'Login successful.');
        } else {

            return $this->sendError('Invalid Login Credentials.', 'Invalid Login Credentials.');
        }


    }
}