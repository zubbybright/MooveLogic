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
        
        try{
        	$make_payment= 

        }
        catch(){

        }

    }
}
	