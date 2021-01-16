<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';        
        $this->validate($request, [
            $fieldType => 'required',
            'password' => 'required',
        ]);

        $fields = array($fieldType => $input[$fieldType], 'password' => $input['password']);
        if($token = auth()->attempt($fields)){
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