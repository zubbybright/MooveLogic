<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;

class ResetPasswordController extends BaseController
{
    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string', 'min:4', 'max:4'],
            'email' => [
                'required', 'email',
                Rule::exists('users')->where(function ($query) use ($request) {
                    return $query->whereToken($request->token)->whereEmail($request->email);
                }),
            ],
        ]);

        return $this->sendResponse("OTP is valid.", "OTP is valid");
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'min:4', 'max:4'],
            'email' => [
                'required', 'email',
                Rule::exists('users')->where(function ($query) use ($request) {
                    return $query->whereToken($request->token)->whereEmail($request->email);
                }),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
            
        User::whereEmail($data['email'])->update(['password' => bcrypt($data['password']) ] );

        return $this->sendResponse("Password reset successful.", "Password reset successful.");
    }

}
