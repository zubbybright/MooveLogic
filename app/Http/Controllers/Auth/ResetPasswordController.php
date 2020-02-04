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
                'email' => ['required', "string", "email"],
                'new_password' => ['required', 'string', 'min:8','confirmed'],
                    
                ]);
                
                
                
                if($data ){                   

                    $user = User::where('email', $data['email']);
                        
                        if ($user == null){
                            return $this->sendError("email is incorrect", "email is incorrect");
                        }
                    $user->password = $data['new_password'];

                    $user->update(['password' => bcrypt($data['new_password'])]);

                    return $this->sendResponse("Password reset successful.", "Password reset successful.");

                }else{

                    return $this->sendError("password reset failed", "password reset failed");
                
                }
                

            }

}
