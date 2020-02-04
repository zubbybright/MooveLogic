<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Notifications\PasswordResetNotification;
use App\Notifications\DatabaseNotification;
use App\User;
use Illuminate\Support\Facades\Password;


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

    use SendsPasswordResetEmails;

    public function sendEmail()
    {
        $user = User::where('email', request()->input('email'))->first();

        if (!$user){

            return $this->sendError('Please enter your registered email address', 'Please enter your registered email address');
        }

        $token = Password::getRepository()->create($user);
        $user->sendPasswordResetNotification($token);

        return $this->sendResponse('Link Sent' , 'Link Sent');
    
    }
}
