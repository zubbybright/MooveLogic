<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;

class ProfileController extends BaseController
{
    //

    public function __construct()
    
    {
        $this->middleware('auth:api');
    }


    public function update(Request $request){
            
            $data = $request->validate([
                'old_password' => ['required', 'string', 'min:8'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

            $user = $request->user();
            
            //edit user password

            $user->password = $data['new_password'];

            $user->save();

            return $this->sendResponse($user, "Password Changed!.");
        }



}
