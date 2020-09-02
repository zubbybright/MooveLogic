<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Notifications\PasswordResetNotification;
use App\Notifications\DatabaseNotification;
use App\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;


class ForgotPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'min:8']
        ]);
        
        $token = rand(1000,9999);
        $email = $data['email'];
        $link =  "moovelogic://reset/$email";

        $validEmail = User::where('email', $email)->first();
        
        if (!$validEmail){
            return $this->sendError('Please enter your registered email address', 'Please enter your registered email address');
        }

        Mail::send(new ResetPassword($token,$email,$link));
        
        User::where('email',$email)->update(['token'=>$token]);

        return $this->sendResponse('Link Sent' , 'Link Sent');
    
    }
}
