<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Trip;
use App\User;
use App\Package;
use App\MooveRequest;
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
            'start_time' => ['date'],
			'current_location' => [ 'string', 'max:100'],
        	'recipient_name' => [ 'required','string', 'max:100'],
        	'recipient_phone_number' => [ 'required','string', 'max:14'],
        	'package_description' =>[ 'string', 'max:255'],
        	'package_type' =>[ 'string'],
        	'size' =>[ 'string'],
        	'weight' =>[ 'string'],
            
    	]);

	}

    protected function create(array $data){

        $trip_cost = 0;
	    $package = Package::create([
            'customer_id' => $data['customer_id'],
            'package_description' => $data['package_description'],
            'size' => $data['size'],
            'weight'=> $data['weight'],
            'package_type'=> $data['package_type'],
            'package_status' => 'ENROUTE'
        ]);


    	$trip= Trip::create([
    		'start_location' => $data['start_location'],
            'end_location' => $data['end_location'],
            'current_location'  => $data['current_location'],
            'start_time' => $data['start_time'],
            'recipient_name' => $data['recipient_name'],
            'recipient_phone_number' => $data['recipient_phone_number'],
            'trip_status' => 'IN_PROGRESS',
            'cost_of_trip' => $trip_cost,
           	'rider_id' => $data['rider_id'],
           	'package_id'=> $package->id,
    	]);

    	
    	if($trip && $package){
            $rider_status = User::where( 'user_type', 'RIDER')
                                    ->where('id',$data['rider_id'] )
                                    ->where('on_a_ride', 0)
                                    ->update(['on_a_ride' => 1,
                                ]);

    		return $this->sendResponse($trip, "Trip started");
    	}else {
    		return response()->json('Cannot start trip.');
    	}

    }

        

    public function end_trip(Request $request){

        $data = $request->validate([
            'end_time' => ['date'],
            'trip_id' => ['required', 'integer'],
            'rider_id' => ['required', 'integer'],
            ]);
        
        try {
            
            $end_trip =Trip::where( 'trip_status', 'IN_PROGRESS')
                        ->where('id', $data['trip_id'])
                        ->update([  'trip_status' => 'ENDED',
                                    'end_time' => $data['end_time'],
                                ]);

            $package_state= Package::where('package_status', 'ENROUTE')
                            ->update(['package_status' => 'DELIVERED',]);

            if ($end_trip && $package_state){

                $rider_status = User::where( 'user_type', 'RIDER')
                        ->where('id',$data['rider_id'] )
                        ->where('on_a_ride', 1)
                        ->update(['on_a_ride' => 0,
                    ]);

                return $this->sendResponse($end_trip, "Trip Ended, Package Delivered !.");
            }else{
                return response()->json('Cannot end trip!');
            }
        }

        catch(\Exception $e){
             return response()->json('Something went wrong.');
        }

    }

    public function make_moove_request(Request $request){
        
        $data = $request->validate([
            'pick_up_location'=>['required','string', 'max:100'],
            'delivery_location'=>['required','string', 'max:100'],
            'customer_id' =>[ 'required','integer'],
            'recipient_name' => [ 'required','string', 'max:100'],
            'recipient_phone_number' => [ 'required','string', 'max:14'],
            'package_description' =>['string', 'max:255'],
            'who_pays'=>[ 'required','string', 'max:100'],
            ]);
        
        $trip_cost = 0;
        

            $moove_request= MooveRequest::create([
                'recipient_name' => $data['recipient_name'],
                'recipient_phone_number' => $data['recipient_phone_number'],
                'package_description' => $data['package_description'],
                'who_pays' => $data['who_pays'],
                'customer_id' => $data['customer_id'],
                'pick_up_location'=> $data['pick_up_location'],
                'delivery_location' => $data['delivery_location'] ,
                'cost_of_trip' => $trip_cost,
                ]);                


                if ($moove_request){
                    //gets the list of all free drivers at pickup location
                    $get_rider = User::where( 'user_type', 'RIDER')
                                    ->where('on_a_ride', 0)
                                    ->where('current_location', $data['pick_up_location'])
                                    ->get();
                    
                        if($get_rider){
                           
                           //gets a particular rider for customer
                           
                            $get_one_rider= array_rand([$get_rider],1 );
                            $found_rider= $get_rider[$get_one_rider];
                    
                                return $this->sendResponse($found_rider, $moove_request , 'Rider Located.');
                                
                        }

                           
                }
                            
                
                else {
                    return response()->json('Cannot locate a rider');
                }



    

        

    }

    public function rider_active_ride(Request $request){

        $data = $request->validate([
            'rider_id' =>[ 'required','integer'],
            'moove_request_id' => ['required','integer']
            ]);

        $moove_ride = MooveRequest::where( 'id', $data['moove_request_id'])
                                    ->get();
        $rider = User::where('id', $data['rider_id'])
                ->get()  ;   

                if($moove_ride &&  $rider)  {
                    return $this->sendResponse($moove_ride, "You have an active ride");
                }                    

    }

    


}   