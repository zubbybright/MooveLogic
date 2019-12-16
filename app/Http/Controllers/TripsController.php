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
   
 //    public function start_trip(Request $request){
 //        $this->validator($request->all())->validate();

 //        return $this->create($request->all());
 //    }

    

	
 //    protected function validator(array $data)
 //    {
	// 	return Validator::make($data, [
 //        	'rider_id' =>[ 'required','integer'],
 //        	'customer_id' =>[ 'required','integer'],
 //        	'start_location' => [ 'required','string'],
 //       		'end_location' => ['required','string'],
 //            'start_time' => ['date'],
	// 		'current_location' => [ 'string', 'max:100'],
 //        	'recipient_name' => [ 'required','string', 'max:100'],
 //        	'recipient_phone_number' => [ 'required','string', 'max:14'],
 //        	'package_description' =>[ 'string', 'max:255'],
 //        	'package_type' =>[ 'string'],
 //        	'size' =>[ 'string'],
 //        	'weight' =>[ 'string'],
            
 //    	]);

	// }

 //    protected function create(array $data){

 //        $trip_cost =1000;
	//     $package = Package::create([
 //            'customer_id' => $data['customer_id'],
 //            'package_description' => $data['package_description'],
 //            'size' => $data['size'],
 //            'weight'=> $data['weight'],
 //            'package_type'=> $data['package_type'],
 //            'package_status' => 'ENROUTE'
 //        ]);


 //    	$trip= Trip::create([
 //    		'start_location' => $data['start_location'],
 //            'end_location' => $data['end_location'],
 //            'current_location'  => $data['current_location'],
 //            'start_time' => $data['start_time'],
 //            'recipient_name' => $data['recipient_name'],
 //            'recipient_phone_number' => $data['recipient_phone_number'],
 //            'trip_status' => 'IN_PROGRESS',
 //            'cost_of_trip' => $trip_cost,
 //           	'rider_id' => $data['rider_id'],
 //           	'package_id'=> $package->id,
 //    	]);

    	
 //    	if($trip && $package){
 //            $rider_status = User::where( 'user_type', 'RIDER')
 //                                    ->where('id',$data['rider_id'] )
 //                                    ->where('on_a_ride', 0)
 //                                    ->update(['on_a_ride' => 1,
 //                                ]);

 //    		return $this->sendResponse($trip, "Trip started");
 //    	}else {
 //    		return response()->json('Cannot start trip.', 400);
 //    	}

 //    }

        

 //    public function end_trip(Request $request){

 //        $data = $request->validate([
 //            'end_time' => ['date'],
 //            'trip_id' => ['required', 'integer'],
 //            'rider_id' => ['required', 'integer'],
 //            ]);
        
 //        try {
            
 //            $end_trip =Trip::where( 'trip_status', 'IN_PROGRESS')
 //                        ->where('id', $data['trip_id'])
 //                        ->update([  'trip_status' => 'ENDED',
 //                                    'end_time' => $data['end_time'],
 //                                ]);

 //            $package_state= Package::where('package_status', 'ENROUTE')
 //                            ->update(['package_status' => 'DELIVERED',]);

 //            if ($end_trip && $package_state){

 //                $rider_status = User::where( 'user_type', 'RIDER')
 //                        ->where('id',$data['rider_id'] )
 //                        ->where('on_a_ride', 1)
 //                        ->update(['on_a_ride' => 0,
 //                    ]);

 //                return $this->sendResponse($end_trip, "Trip Ended, Package Delivered !.");
 //            }else{
 //                return response()->json('Cannot end trip!', 400);
 //            }
 //        }

 //        catch(\Exception $e){
 //             return response()->json('Something went wrong.', 400);
 //        }

 //    }

    /**
     *
     */
    
    public function calculateCost(){

        $trip_cost = 1000;
    }


    public function findRider(Request $request){
        //find a free rider in the location of the customer
        // - if there is no rider, return "No rider available"
        //save the trip information using the available rider
        $data = $request->validate([
            'start_location'=>['nullable', 'max:100'],
            'end_location'=>['required','string', 'max:100'],
            'recipient_name' => [ 'required','string', 'max:100'],
            'recipient_phone_number' => [ 'required','string', 'max:14'],
            'package_description' =>['string', 'max:255'],
            'who_pays'=>[ 'required','string', 'max:100'],
            'payment_method'=>['required', 'string', 'max:100']
            ]);
        
        $trip_cost = 1000;
        

            $findRider= Trip::create([
                'recipient_name' => $data['recipient_name'],
                'recipient_phone_number' => $data['recipient_phone_number'],
                'package_description' => $data['package_description'],
                'who_pays' => $data['who_pays'],
                'customer_id' => auth()->user('id'),
                'start_location'=> $data['start_location'],
                'end_location' => $data['end_location'] ,
                'cost_of_trip' => $trip_cost,
                'payment_method' => $data['payment_method']
                ]);                


                if ($findRider){


                    //gets the list of all free drivers at pickup location and select one randomly
                    $get_riders = User::where( 'user_type', 'RIDER')
                                    ->where('on_a_ride', 0)
                                    ->where('current_location', $data['start_location'])
                                    ->where('active_ride', 0)
                                    ->inRandomOrder()->take(1)->get();
                                    //gets profile of selected rider:
                    $get_rider= $get_riders->first()->profile; 

                    //update active ride status of selected rider :
                        if($get_rider){
                            User::where( 'user_type', 'RIDER')
                                    ->where('on_a_ride', 0)
                                    ->where('current_location', $data['start_location'])
                                    ->where('active_ride', 0)
                                    ->update(['active_ride' => 1]);
                           
                                return $this->sendResponse($get_riders,'Rider located! Your trip cost is '.$trip_cost);
                                
                        }else{

                            return $this->sendError("No rider Available", 'Try again later', 400);
                        }

                           
                }
                            
                
                else {

                    return $this->sendError("Cannot locate a rider", 'Cannot locate a rider', 400);
                }   
        

    }

    public function rider_active_ride(Request $request){

        $data = $request->validate([
            'current_location' =>[ 'required','string'],
            'rider_id' =>['required', 'string']
            ]);

            $rider= User::where('active_ride',1)
                        ->get();
            
            if($rider){

            $get_ride =Trip::where('start_location', $data['current_location'])
                    ->get() ;

                return $this->sendResponse($get_ride, "You have an active ride");
            }

            else{
                 return $this->sendError("Cannot get active ride", 'Cannot get active ride', 400);
            }

                                    

    }

    


}   