<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Hash;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationDate;
// use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Admin;



class ProfileController extends BaseController
{
    //

        public function __construct()

        {
            $this->middleware('auth:api');
        }


        public function updatePassword(Request $request){
            //validate input:
            $data = $request->validate([
                'old_password' => ['required', 'string', 'min:8'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

                //check if old and new passwords are the same.
                if( $data['old_password'] ===$data['new_password']){

                    return $this->sendError("The new password must be different from the old password.", "Please Change your password.");
                }

                else{

                    $user = $request->user();


                    if (Hash::check($data['old_password'], $user->password )){

                        $user->password = $data['new_password'];

                        $user->save();

                        return $this->sendResponse($user, "Password Changed!.");

                    }else{

                            return $this->sendError("Password could not be changed.","Old password does not match existing password.");
                        }

                }

        }


        // public function addBankCard(Request $request){

        //     $data = $request->validate([
        //         'card_number' => ['required',  new CardNumber],
        //         'card_name' => ['required', 'string', 'max:255'],
        //         'expiration_date' => ['required', new CardExpirationDate($request->input('expiration_date'))],
        //         //new CardExpirationMonth($request->input('expiration_month'))],
        //         // 'expiration_year' => ['required','digits:4'] ,//new CardExpirationYear($request->input('expiration_year'))],
        //         'cvv' => ['required', new CardCvc($request->input('card_number'))]
        //         ]);

        //     $card_details = auth()->user()->profile()
        //                     ->update([  'card_number' => $data['card_number'],

        //                                 'card_name' => $data['card_name'],
        //                                 'cvv' => $data['cvv'],
        //                                 'expiration_month' => $data['expiration_month'],
        //                                 'expiration_year' => $data['expiration_year'],
        //                             ]);
        //     $profile = auth()->user()->profile()->get();
        //     if($card_details){
        //         return $this->sendResponse($profile, "Card Details Saved.");
        //     }

        //     else{
        //         return $this->sendError('Cannot add card!', 'Cannot add card!');
        //     }

        // }



        public function addProfilePic(Request $request)
        {
            try{

                $request->validate([
                    'profile_pic'     =>  'required|image|mimes:jpeg,png,jpg,gif,base64|max:2048'
                ]);

                    //using spatie media collection:
                $profile = auth()->userOrFail()->profile;

                $dp= $profile->addMedia($request->profile_pic)->toMediaCollection();

                return $this->sendResponse($dp, "Profile Picture saved.");
            }
            catch(\Exception $e){
                return $this->sendError("Profile Picture Not Saved", 'Profile Picture Not Saved');
            }

        }

    public function feedback(Request $request){
        $data = $request->validate([
                'feedback_type' => ['required','string'],
                'feedback_description'     =>  ['required','string',' max: 1000'],
            ]);
        try{

        $user = auth()->user();
        $profile = $user->profile;

        $feedback = new Feedback;

        $feedback->feedback_description = $data['feedback_description'];
        $feedback->feedback_type= $data['feedback_type'];
        $feedback->user_id= $user->id;
        $feedback->profile_id= $profile->id;

        $feedback->save();

        return $this->sendResponse($feedback, "Your feedback has been submitted, Thank You.");
        }

        catch(\Exception $e){
            return $this->sendError("Your feedback could not be submitted at the moment", "Your feedback could not be submitted at the moment");
        }


    }


    public function createAdmin(Request $request){
        $data = $request->validate([
            'first_name'=>['required','string', 'max:100'],
            'last_name'=>['required','string', 'max:100'],
            'username' => [ 'required','string', 'max:100'],
            'email' => ['required','string', 'email', 'max:100'],
            'password' =>['required','string', 'max:100'],
            'phone_number'=>['required','string', 'max:100']
        ]);



        $admin = new Admin;
            $admin->first_name = $data['first_name'];
            $admin->last_name = $data['last_name'];
            $admin->username = $data['username'];
            $admin->email = $data['email'];
            $admin->password = $data['password'];
            $admin->phone_number= $data['phone_number'];
            $admin->save();


        return $this->sendResponse($admin, 'Admin registered successfully.');
    }

    public function getProfile(){
        
        $user= auth()->user();
        $profile = $user->profile;
        
        if($profile == null){
            return $this->sendError("Profile does not exist", "Profile does not exist");
        }

        $info  = [
            'user'=> $user,
            'profile' => $profile,
            ];
        return $this->sendResponse($info, "Your Profile.");

    }






}
