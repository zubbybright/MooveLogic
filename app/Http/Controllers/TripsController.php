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
   
    public function startTrip($tripId){
        //locate the trip
        //check the current status of the trip
        // if in progress return already started
        //update status to in progress

        $trip = Trip::find($tripId);
        if('trip_status', 'IN_PROGRESS'){
            return $this->sendError("Trip already started!");
        }
        else{
            $trip->update('trip_status', 'IN_PROGRESS');
        }
                    
    		return $this->sendResponse($trip, "Trip started");
    	}else {
            return $this->sendResponse("Cannot start trip");
    	}

    }

        

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
    
    public function calculateCost(Request $request){

        $data = $request->validate([
            'start_location'=>['nullable', 'max:100'],
            'end_location'=>['required','string', 'max:100'],
            'package_description' =>['string', 'max:255'],
            ]);

        $trip_cost = $this->_calculateCostOfTrip();

            return $this->sendResponse($trip_cost, 'Estimated cost of trip' );
    }

    private function _calculateCostOfTrip(){
        return "1000 - 2000";
    }


    public function findRider(){
    //find a free rider in the location of the customer:
        //get the customer's start location
        //look for riders in the customer's location that has rider_status is false
            //how do we get the rider's current location
                //pin the location from the map?
                //assign the location by default at register
            //how do we know that a rider is free
                //if rider id is not attatced to trip with pending or in progress status
        //pick preferred rider
        
    //if there is no rider, return "No rider available"
    //save the trip information using the available rider
    }

    // public function findRider(Request $request){

    //     $data = $request->validate([
    //         'start_location'=>['nullable', 'max:100'],
    //         'end_location'=>['required','string', 'max:100'],
    //         'recipient_name' => [ 'required','string', 'max:100'],
    //         'recipient_phone_number' => [ 'required','string', 'max:14'],
    //         'package_description' =>['string', 'max:255'],
    //         'who_pays'=>[ 'required','string', 'max:100'],
    //         'payment_method'=>['required', 'string', 'max:100']
    //     ]);
        
    //     $trip_cost = $this->_calculateCostOfTrip();
        

    //         $findRider= Trip::create([
    //             'recipient_name' => $data['recipient_name'],
    //             'recipient_phone_number' => $data['recipient_phone_number'],
    //             'package_description' => $data['package_description'],
    //             'who_pays' => $data['who_pays'],
    //             'customer_id' => auth()->user('id'),
    //             'trip_status' => 'PENDING',
    //             'start_location'=> $data['start_location'],
    //             'end_location' => $data['end_location'] ,
    //             'cost_of_trip' => $trip_cost,
    //             'payment_method' => $data['payment_method']
    //             ]);                


    //             if ($findRider){


    //                 //gets the list of all free drivers at pickup location and select one randomly
    //                 $get_riders = User::where( 'user_type', 'RIDER')
    //                                 ->where('on_a_ride', 0)
    //                                 ->where('current_location', $data['start_location'])
    //                                 ->where('active_ride', 0)
    //                                 ->inRandomOrder()->take(1)->get();
    //                                 //gets profile of selected rider:
    //                 $get_rider= $get_riders->first()->profile; 

    //                 //update active ride status of selected rider :
    //                     if($get_rider){
    //                         User::where( 'user_type', 'RIDER')
    //                                 ->where('on_a_ride', 0)
    //                                 ->where('current_location', $data['start_location'])
    //                                 ->where('active_ride', 0)
    //                                 ->update(['active_ride' => 1]);
                           
    //                             return $this->sendResponse($get_riders,'Rider located!');
                                
    //                     }else{

    //                         return $this->sendError("No rider Available", 'Try again later', 400);
    //                     }

                           
    //             }
                            
                
    //             else {

    //                 return $this->sendError("Cannot locate a rider", 'Cannot locate a rider', 400);
    //             }   
        

    // }

    public function findActiveTrip(){
        //get the rider's id
        //find a trip with this rider's id and with status pending
        //if found return tthe trip
        //if not found return "No trip found"
        $rider = auth()->user();
        $trip = Trip::where('rider_id', $rider->id)
                    ->where('trip_status','PENDING')->first();
            if($trip){
                return $this->sendResponse($trip, 'This is your active ride');
            }
            else{

                return $this->sendError('No trip found');
            }

    }

    


}   