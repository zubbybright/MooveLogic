<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Trip;
use App\User;
use App\Package;
use App\Profile;
use App\Payment_Method;
use App\Http\Controllers\BaseController;

class PaymentController extends BaseController
{
    //


    public function make_payment(Request $request){

        $data = $request->validate([
            'card_option' => ['string'],
            'cash_on_delivery'=>['string'],
            ]);
        
        if($data['card_option']){

            $register_card = auth()->user()->profile()
                ->whereNull('card_number')
                ->whereNull('card_name')
                ->whereNull('cvv')
                ->whereNull('expiration_month')
                ->whereNull('expiration_year');

                if($register_card){

                    return response()->json('Please add your card Details.');
                }
                else{

                    return response()->json('card_option method selected!');

                }
        }elseif($data['cash_on_delivery']) {

            return response()->json('cash on delivery method selected!');
        }

        else{

           return response()->json('cannot make payment', 400); 
        }

    }
}
	