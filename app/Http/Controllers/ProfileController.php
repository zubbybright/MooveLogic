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


    public function update_password(Request $request){
            
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

    public function add_bank_card(Request $request){
        
        $data = $request->validate([
            'card_number' => ['required', 'string', 'max:16', 'min:16'],
            'card_name' => ['required', 'string', 'max:255'],
            'cvv' => ['required', 'string', 'max:3', 'min:3'],
            'expiry_date'=>['required', 'date']
            ]);

        $card_details = auth()->user()->profile()
                        ->update([  'card_number' => $data['card_number'],
                                    'card_name' => $data['card_name'],
                                    'cvv' => $data['cvv'],
                                    'expiry_date' => $data['expiry_date'],
                                ]);
        if($card_details){
            return $this->sendResponse($card_details, "Card Details Saved.");           
        }

        else{
            return response()->json('Cannot add card!');
        }


        
    }



}
