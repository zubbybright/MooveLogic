<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;

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


          if( $user->save()){

            return $this->sendResponse($user, "Password Changed!.");
            } else{
                return response()->json("Cannot Change pasword.", 400);
            }
    }


    public function add_bank_card(Request $request){
        
            $data = $request->validate([
                'card_number' => ['required',  new CardNumber],
                'card_name' => ['required', 'string', 'max:255'],
                'expiration_month' => ['required', 'string','min:1','max:2'],
                //new CardExpirationMonth($request->input('expiration_month'))],
                'expiration_year' => ['required','digits:4'] ,//new CardExpirationYear($request->input('expiration_year'))],
                'cvv' => ['required', new CardCvc($request->input('card_number'))]
                ]);

            $card_details = auth()->user()->profile()
                            ->update([  'card_number' => $data['card_number'],
                           
                                        'card_name' => $data['card_name'],
                                        'cvv' => $data['cvv'],
                                        'expiration_month' => $data['expiration_month'],
                                        'expiration_year' => $data['expiration_year'],
                                    ]);
            if($card_details){
                return $this->sendResponse($card_details, "Card Details Saved.");           
            }

            else{
                return response()->json('Cannot add card!', 400);
            }
            
    }

    public function add_profile_pic(Request $request){

        $this->validate($request, [
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try{
           if ($request->hasFile('file')) {
                $image = $request->file('file');
                $name = $image->getClientOriginalName();
                $size = $image->getClientSize();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);

                $profile_pic = new Profile;
                $profile_pic->profile_pic = $name;
                $profile_pic->save();

                return $this->sendResponse($profile_pic, "Profile Picture saved.");
            } else {

                return response()->json('Cannot upload your profile picture.', 400);
            }
        }

            catch(\Exception $e){
             return response()->json('Something went wrong.', 400);
        }

    }

}
