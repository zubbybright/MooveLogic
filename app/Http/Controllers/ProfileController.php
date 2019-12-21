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
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;



class ProfileController extends BaseController
{
    //

        public function __construct()
        
        {
            $this->middleware('auth:api');
        }


        public function update_password(Request $request){
            //validate input:
            $data = $request->validate([
                'old_password' => ['required', 'string', 'min:8'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

                //check if old and new passwords are the same.
                if( $data['old_password'] ===$data['new_password']){
                    
                    return $this->sendError("The new password must be different from the old password.", "Please Change your password.", 400);
                }

                else{

                    $user = $request->user();
                    

                    if (Hash::check($data['old_password'], $user->password )){

                        $user->password = $data['new_password'];

                        $user->save();

                        return $this->sendResponse($user, "Password Changed!.");

                    }else{

                            return $this->sendError("Password could not be changed.","Old password does not match existing password.", 400);
                        }
                  
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
            $profile = auth()->user()->profile()->get();
            if($card_details){
                return $this->sendResponse($profile, "Card Details Saved.");           
            }

            else{
                return response()->json('Cannot add card!', 400);
            }
            
    }



        public function add_profile_pic(Request $request)
        {
            try{

                $request->validate([
                    'profile_pic'     =>  'required|image|mimes:jpeg,png,jpg,gif,base64|max:2048'
                ]);

                    //using spatie media collection:
                $profile = auth()->userOrFail()->profile;

                $dp= $profile->addMedia($request->profile_pic)->toMediaCollection();

                return $this->sendResponse($profile, $dp, "Profile Picture saved.");
            }
            catch(\Exception $e){
                return $this->sendError("Profile Picture Not Saved", 'Profile Picture Not Saved', 400);
            }

        }
         
 }
