<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BaseController;


class ForgotPasswordController extends BaseController
{
    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email','min:8', 'exists:users']
        ]);
        
        $this->sendEmailAndUpdate($data['email']);
    }

    public function resendEmail($email)
    {
        $this->sendEmailAndUpdate($email);
    }

    private function sendEmailAndUpdate($email)
    {
        $token = rand(1000,9999);

        Mail::send(new ResetPassword($token,$email));
        
        User::where('email',$email)->update(['token'=>$token]);

        return $this->sendResponse('Link Sent' , 'Link Sent');
    }
}
