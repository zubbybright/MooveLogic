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

        //how do I update the status of the package to enroute?
            //get the id of package on trip
            //find it on package model
            //update status to enroute


        $trip = Trip::find($tripId);
        if('trip_status'=== 'IN_PROGRESS'){
            return $this->sendError("Trip already started!");

            $startTrip = $trip->update('trip_status', 'IN_PROGRESS');
           
                return $this->sendResponse($trip, "Trip started");  
        }
    		
    	else {
            return $this->sendResponse("Cannot start trip", "Cannot start trip");
    	}

    }

    public function endTrip($tripId){
        //locate the trip
        //check the current status of the trip
        // if ended return already ended
        //update status to ended

         //how do I update the status of the package to delivered?
            //get the id of package on trip
            //find it on package model
            //update status to delivered

        $trip = Trip::find($tripId);
        if('trip_status'=== 'ENDED'){
            return $this->sendError("Trip already ended!");

            $endTrip = $trip->update('trip_status', 'ENDED');
           
                return $this->sendResponse($trip, "Trip has ended");  
        }
            
        else {
            return $this->sendResponse("Cannot end trip", "Cannot start trip");
        }
    }
        


    
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


    public function findRider(Request $request){
        //create package
        //create trip
        //find a free rider in the location of the customer:
            //get the customer's start location
            //look for riders in the customer's location
                //how do we get the rider's current location
                    //pin the location from the map?
                    //assign the location by default at register***
                //how do we know that a rider is free
                    //if rider id is not attatched to trip with pending or in progress status
            //pick preferred rider
            
        //if there is no rider, return "No rider available"
        //save the trip information using the available rider
        //set package status to Pending.

        $data = $request->validate([
            'start_location'=>['nullable', 'max:100'],
            'end_location'=>['required','string', 'max:100'],
            'recipient_name' => [ 'required','string', 'max:100'],
            'recipient_phone_number' => [ 'required','string', 'max:14'],
            'package_description' =>['string', 'max:255'],
            'who_pays'=>[ 'required','string', 'max:100'],
            'payment_method'=>['required', 'string', 'max:100']
        ]);

        $trip_cost = $this->_calculateCostOfTrip();

             //create package:
            $package = Package::create([
                'package_description' => $data['package_description'],
                'customer_id' => auth()->user()->id,
                'package_status' => 'PENDING'
            ]);

             //create trip:
            $findRider= Trip::create([
                'recipient_name' => $data['recipient_name'],
                'recipient_phone_number' => $data['recipient_phone_number'],
                'package_description' => $data['package_description'],
                'who_pays' => $data['who_pays'],
                'customer_id' => auth()->user()->id,
                'trip_status' => 'PENDING',
                'start_location'=> $data['start_location'],
                'end_location' => $data['end_location'] ,
                'cost_of_trip' => $trip_cost,
                'payment_method' => $data['payment_method'],
                'package_id' => $package->id
                ]);   

            if ($findRider){
                               
                //get id's of all riders not on a trip and randomly select one:
                $freeRider= Trip::where('trip_status', 'ENDED')
                                ->where('trip_status', 'CANCELLED')
                                ->inRandomOrder()->take(1)->find('rider_id');
                 
                 //check if selected rider is in customer's location:
                $riderLocation = User::where( 'id', $freeRider)
                                ->where('current_location', $data['start_location'])->first();
                //get the profile of the selected rider:
                $riderProfile = $riderLocation->profile;

                //update trip with selected rider id:

                Trip::where('package_id',$package->id)
                        ->update(['rider_id'=>$riderLocation->id]);

                    return $this->sendResponse($riderLocation, $riderProfile, 'Rider located!');

            } else {

                return $this->sendError("Cannot locate a rider at the moment", 'Cannot locate a rider at the moment');
            }
            
            
    }

    public function findActiveTrip(){
        //get the rider's id
        //find a trip with this rider's id and with status pending
        //if found return the trip
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