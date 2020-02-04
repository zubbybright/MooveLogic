<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\User;

class ResetPasswordController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    // use ResetsPasswords;

    // protected function sendResetResponse(Request $request, $response)
    // {
    //     return $this->sendResponse("Password has been reset", "Password has been reset");
    // }

    // protected function sendResetFailedResponse(Request $request, $response)
    // {
    //      return $this->sendError("Password reset failed", "Password reset failed");
    // }

            public function reset(Request $request){
            //validate input:
            $data = $request->validate([
                'new_password' => ['required', 'string', 'min:8','confirmed'],
                    
                ]);
               
                if($data){

                    $user = new User;

                    $user->password = $data['new_password'];

                    $user->save();

                    return $this->sendResponse($user, "Password reset successful.");

                }else{

                    return $this->sendError("password reset failed", "password reset failed");
                
                }
                

            }

}
