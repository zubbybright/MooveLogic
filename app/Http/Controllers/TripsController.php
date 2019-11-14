<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Trip;
use App\User;
use App\Package;

class TripsController extends Controller
{
    //
   
    public function start_trip(){

    	protected function validator(array $data)
    	
    	{
    		return Validator::make($data, [
           	'start_location' => [ 'required','string', 'max:255'],
           	'end_location' => ['required','string', 'max:255'],
    		'current_location' => [ 'string', 'max:255'],
          	'start_time' => ['required','string','date'],
		    'end_time' => ['required','string','date'],
		    'cost_of_trip' => [ 'string', 'max:255'],
		    'end_location' =>[ 'string', 'max:255'],
		    'trip_status' =>[ 'string', 'max:255'],
            'recipient_name' => [ 'required','string', 'max:255'],
            'recipient_phone_number' => [ 'required','string', 'max:255'],
            'rider_id' =>[ 'required','string', 'max:255'],
            'package_id' =>[ 'required','string', 'max:255'],

        	]);

    	}

	    protected function create(array $data){
	    	$trip= Trip::create([

	    		'start_location' => $data['start_location'],
	            'end_location' => $data['end_location'],
	            'current_location'  => $data['current_location'],
	            'start_time' => $data['start_time' ],
	            'end_time' => $data['end_time'],
	            'cost_of_trip' => 0,
	           	'rider_id' => $user->id,
	           	'package_id'=> $package->id,



	    	]);

	    	    $package = Package::create([
	            'customer_id' => $user->id
	            'description' => $data['description'],
	            'size' => $data['size'],
	            'weight'=> $data['weight'],
	            'package_type'=> $data['package_type'],
	        ]);

	    	
	    	if($trip){
	    		$status = "trip_status"=>"IN PROGRESS";					
					return response()->json('Trip has started.');
	    	}else {

	    		return response()->json('Cannot start trip.');
	    	}

	    }

	    
	    
  
    }
    
       
   
    

       
   
}
