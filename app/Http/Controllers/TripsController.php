<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Trip;
use App\User;
use App\Package;
use App\Http\Controllers\BaseController;

class TripsController extends BaseController
{
    //
   
    public function start_trip(Request $request){
        $this->validator($request->all())->validate();

        return $this->create($request->all());
    }

	protected function validator(array $data)
    {
		return Validator::make($data, [
        	'rider_id' =>[ 'required','integer'],
        	'customer_id' =>[ 'required','integer'],
        	'start_location' => [ 'required','string'],
       		'end_location' => ['required','string'],
			'current_location' => [ 'string', 'max:100'],
      		'start_time' => ['date'],
	    	'end_time' => ['date'],
        	'recipient_name' => [ 'required','string', 'max:100'],
        	'recipient_phone_number' => [ 'required','string', 'max:14'],
        	'package_description' =>[ 'string', 'max:255'],
        	'package_type' =>[ 'string'],
        	'size' =>[ 'string'],
        	'weight' =>[ 'string'],
    	]);

	}

    protected function create(array $data){

	    $package = Package::create([
            'customer_id' => $data['customer_id'],
            'package_description' => $data['package_description'],
            'size' => $data['size'],
            'weight'=> $data['weight'],
            'package_type'=> $data['package_type'],
        ]);


    	$trip= Trip::create([
    		'start_location' => $data['start_location'],
            'end_location' => $data['end_location'],
            'current_location'  => $data['current_location'],
            'start_time' => $data['start_time' ],
            'end_time' => $data['end_time'],
            'recipient_name' => $data['recipient_name'],
            'recipient_phone_number' => $data['recipient_phone_number'],
            'status' => 'IN_PROGRESS',
            'cost_of_trip' => 0,
           	'rider_id' => $data['rider_id'],
           	'package_id'=> $package->id,
    	]);

    	
    	if($trip){
    		return $this->sendResponse($trip, "Trip started")
    	}else {
    		return response()->json('Cannot start trip.');
    	}

    }

}   