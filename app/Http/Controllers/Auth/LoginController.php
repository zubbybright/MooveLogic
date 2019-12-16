<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    public function login()
    {
        $credentials = request(['phone_number', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return $this->sendError("Invalid phone number or password!", 'Invalid phone number or password!.', 400);
        }

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
    }

}
