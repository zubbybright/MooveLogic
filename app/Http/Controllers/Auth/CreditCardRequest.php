<?php

namespace App\Http\Controllers\Auth;


use App\Profile;
use App\User;
use Illuminate\Http\Request;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;

class CreditCardRequest extends FormRequest
{
    //
        public function addBankCard(Request $request){
        
	        $data = $request->validate([
	            'card_number' => ['required',  new CardNumber],
	            'card_name' => ['required', 'string', 'max:255'],
	            'expiration_month' => ['required', new CardExpirationMonth($this->get('expiration_month'))],
            	'expiration_year' => ['required', new CardExpirationYear($this->get('expiration_year'))],
            	'cvv' => ['required', new CardCvc($this->get('card_number'))]
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
	            return response()->json('Cannot add card!','Cannot add card!');
	        }


	        
    	}
}
