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

        try{

        $alreadyStarted = Trip::where('id',$tripId)
                            ->where('trip_status','IN_PROGRESS')->first();
                
                if($alreadyStarted){
                    return $this->sendError("Trip already started!");
                }

                else{
                    $startTrip = Trip::where('id',$tripId)
                                ->update(['trip_status'=>'IN_PROGRESS']);
           
                        return $this->sendResponse($startTrip, "Trip started");  
                }

        }   
    		
    	catch(\Exception $e) {
            return $this->sendError("Cannot start trip", "Cannot start trip");
    	}

    }

    public function endTrip($tripId){
        //locate the trip
        //check the current status of the trip
        // if ended return already ended
        //update status to ended
        //update rider on a ride to false.

            try{
                //locate the trip
        $alreadyEnded = Trip::where('id',$tripId)
                            ->where('trip_status','ENDED')->first();
                //check the current status of the trip
                // if ended return already ended
                if($alreadyEnded){
                    return $this->sendError("Trip already ended!");
                }

                else{
                    //update status to ended

                    $endTrip = Trip::where('id',$tripId)
                                ->update(['trip_status'=>'ENDED']);

                        //update rider on a ride to false:
                    $rider= auth()->user();
                    $freeRider= User::where('id', $rider->id)
                                ->update(['on_a_ride'=> 0]);
           
                        return $this->sendResponse($endTrip, "Trip ended");  
                }

        }   
            
        catch(\Exception $e) {
            return $this->sendError("Cannot end trip", "Cannot end trip");
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
                    //assign the location by default at register***
                //check if rider is free
                    //rider is free if on a ride in false.
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

                $Rider =  User::where( 'user_type', 'RIDER')
                                ->where('current_location', $data['start_location'])
                                ->where('on_a_ride', 0)
                                ->inRandomOrder()->take(1)->first();

                        if(!$Rider){

                            Trip::where('package_id',$package->id)
                                    ->update(['trip_status'=>"CANCELLED"]);
                            
                            return $this->sendError("No rider available at the moment. Please try again later", "No rider available at the moment");
                        }
                       
                       else{

                            //get the profile of the selected rider:

                            $riderProfile = $Rider->profile->get();

                            //update trip with selected rider id:

                            Trip::where('package_id',$package->id)
                                    ->update(['rider_id'=>$Rider->id]);
                            
                            //update rider ride status:
                            User::where('id', $Rider->id)
                                ->update(['on_a_ride'=> 1]);

                                return $this->sendResponse($Rider, 'Rider located!');
                        }                
                
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

    public function riderTripHistory(){

        //get te rider id
        //check ended trips with rider id
        //get

        $rider = auth()->user();
        $tripHistory = Trip::where('rider_id', $rider->id)
                        ->where('trip_status', 'ENDED')->get();
                
                if($tripHistory){
                    return $this->sendResponse($tripHistory, 'Your trip history');
                }
                else{
                    return $this->sendError('You have no history yet', 'You have no history yet');
                }
    }

    public function customerOrderHistory(){
         //get te user id
        //check trips with customer id
        //get
        $user= auth()->user();
        $orderHistory = Trip::where('customer_id', $user->id)->get();
            
            if($orderHistory){
                    return $this->sendResponse($orderHistory, 'Your history');
                }
                else{
                    return $this->sendError('You have no order history yet', 'You have no order history yet');
                }
    }

    public function packageDelivered($id){
        //get the package id 
        //check in package model if package is already delivered
            //if delivered respond package already delivered
        //if not, update package status as delivered.
        $alreadyDelivered = Package::where('id', $id)
                            ->where('package_status', 'DELIVERED')->first();
                if($alreadyDelivered){
                    return $this->sendError("This package has already been delivered!");
                }
                else{

                    $packageDelivered = Package::where('id',$id)
                                        ->update(['package_status'=>'DELIVERED']);

                            return $this->sendResponse("Package Delivered!", "Package Delivered!");
                }
                

    }

    


}   