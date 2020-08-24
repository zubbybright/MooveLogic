<?php

namespace App\Http\Controllers\Auth;


use App\Profile;
use App\User;
use Illuminate\Http\Request;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use LVR\CreditCard\CardExpirationDate;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;

class CreditCardRequest extends BaseController
{
    //
        public function rules(Request $request){
        
	        $data = $request->validate([
	            'card_number' => ['required', 'string'],
	            'card_name' => ['required', 'string', 'max:255'],
	            'expiration_date' => ['required', 'date_format:m/y'],
            	// 'expiration_year' => ['required', new CardExpirationYear($this->get('expiration_year'))],
            	'cvv' => ['required', 'string']
	            ]);

	        $card_details = auth()->user()->profile()
	                        ->update([  'card_number' => $data['card_number'],
	                                    'card_name' => $data['card_name'],
	                                    'cvv' => $data['cvv'],
	                                    'expiration_date' => $data['expiration_date'],
	                                ]);
	        if($card_details){
				
	            return $this->sendResponse("Card Details Saved.", "Card Details Saved.");           
	        }

	        else{
	            return response()->json('Cannot add card!','Cannot add card!');
	        }


	        
    	}
}
