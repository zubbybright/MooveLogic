<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Models\User;

class ResetPasswordController extends BaseController
{
    public function validateToken(Request $request)
    {
        $data = $request->validate(['otp' => ['required', 'string', 'min:4', 'max:4']]);

        $validToken = User::whereToken($data['otp'])->first();

        if ($validToken == null) {
            return $this->sendError("Invalid OTP code.", "Invalid OTP code.");
        }
        return $this->sendResponse("OTP is valid.", "OTP is valid");
    }

    public function reset(Request $request, $otp)
    {

        $data = $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::whereToken($otp)->first();
        if ($user == null) {
            return $this->sendError("otp is invalid", "otp is invalid");
        }

        $user->password = $data['new_password'];
        $user->save();

        return $this->sendResponse("Password reset successful.", "Password reset successful.");
    }
}
