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
            $profile = auth()->user()->profile()->get();
            if($card_details){
                return $this->sendResponse($profile, "Card Details Saved.");           
            }

            else{
                return response()->json('Cannot add card!', 400);
            }
            
    }


            //trait for file upload:

        public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
        {
            $name = !is_null($filename) ? $filename : Str::random(25);

            $file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);

            return $file;
        }

        public function add_profile_pic(Request $request)
        {
            try{

                $request->validate([
                    'profile_pic'     =>  'required|image|mimes:jpeg,png,jpg,gif|max:2048'
                ]);

                // Get current user profile
                $profile = auth()->userOrFail()->profile; 
                
                    // Check if a profile image has been uploaded:
                if ($request->has('profile_pic')) {
                        // Get image file from input:
                    $image = $request->file('profile_pic');
                        // Make an image name 
                    $name = $image->getClientOriginalName();
                        // Define folder path
                    $folder = '/images/';
                        // Make a file path where image will be stored [ folder path + file name + file extension]
                    $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
                        // Upload image
                    $this->uploadOne($image, $folder, 'public', $name);
                        // Set user profile image path in database to filePath
                    $profile->profile_pic = $filePath;

                }
                    // save record to database
                $profile->save();

                    return $this->sendResponse($profile, "Profile Picture saved.");
            }
            catch(\Exception $e){
                return $this->sendError("Profile Picture Not Saved", 'File must be an image', 400);
            }

        }
        
 }
