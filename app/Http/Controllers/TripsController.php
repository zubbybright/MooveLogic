<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Trip;
use App\User;
use App\Package;
use App\RiderLocation;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

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
        if($trip == null){
            return $this->sendError("Trip does not exist");
        }
        if($trip->trip_status == "IN_PROGRESS"){
            return $this->sendError("Trip already started!");
        }
        else{
            $trip->trip_status = 'IN_PROGRESS';
            $trip->save();
   
            return $this->sendResponse($trip, "Trip started");  
        }

        return $this->sendError("Cannot start trip", "Cannot start trip");

    }

    public function endTrip($tripId){
        //locate the trip
        //  check if the trip exist
        //check the current status of the trip
        // if ended return already ended
        //update status to ended
        //update rider on a ride to false.

            $trip = Trip::find($tripId);

            if($trip == null){
                return $this->sendError("Trip does not exist");
            }

            if($trip->trip_status == "ENDED"){
                return $this->sendError("Trip already ended!");
            }

            else{
                //update status to ended
                $trip->trip_status = "ENDED";
                $trip->save();


                //update rider on a ride to false:
                $rider= auth()->user();
                $rider->on_a_ride = false;
                $rider->save();

                return $this->sendResponse($trip, "Trip ended");  
            }            
           

    }

    public function cancelTrip($tripId, $riderId){
            $trip = Trip::find($tripId);

            if($trip == null){
                return $this->sendError("Trip does not exist");
            }

            if($trip->trip_status == "CANCELLED"){
                return $this->sendError("Trip has already been cancelled!");
            }

            else{
                //update status to cancelled
                $trip->trip_status = "CANCELLED";
                $trip->save();


                //update rider on a ride to false:
                // $riderId= Trip::where('id', $tripId)
                //             ->get('rider_id');

                $rider = User::find($riderId);
                $rider->on_a_ride = false;
                $rider->save();

                return $this->sendResponse($trip, "Trip cancelled.");  
            }            
           
    }
        

    private function _estimatedCostOfTrip(){
        return "1000 - 2000";
    }

    public function calculateCost(Request $request){

        $data = $request->validate([
            'start_location'=>['nullable', 'max:100'],
            'end_location'=>['required','string', 'max:100'],
            'package_description' =>['string', 'max:255'],
            "km" => ['required','string', 'max:100'],
            "time" => ['required','string', "max:100"]
            ]);

        $timeInMin = ($data['time'] * 60);
        $baseFare = 200; 
        $calculateCost = $baseFare + ($timeInMin * $data['km']);

        
        return $this->sendResponse($calculateCost, 'Cost of trip' );
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


        //find free rider in customer location
            //if rider not found, ask customer to try again

        $data = $request->validate([
            'start_location'=>['nullable', 'max:100'],
            'end_location'=>['required','string', 'max:100'],
            'recipient_name' => [ 'required','string', 'max:100'],
            'recipient_phone_number' => [ 'required','string', 'max:14'],
            'package_description' =>['string', 'max:255'],
            'who_pays'=>[ 'required','string', 'max:100'],
            'payment_method'=>['required', 'string', 'max:100']
        ]);

        if(!$data){
            return $this->sendError("Something is wrong with your input", "Something is wrong with your input");
        }
        else{

        $rider =  User::where( 'user_type', 'RIDER')
            ->where('current_location', $data['start_location'])
            ->where('on_a_ride', 0)
            ->where('id', 15)->first();
            // ->inRandomOrder()->take(1)->first();

         if(!$rider){
            return $this->sendError("No rider available at the moment. Please try again later", "No rider available at the moment");
        }

        $trip_cost = $this->_estimatedCostOfTrip();

        try{
         //create package:
        
        $package = Package::create([
            'package_description' => $data['package_description'],
            'customer_id' => auth()->user()->id,
            'package_status' => 'PENDING'
        ]);

             //create trip:
        $trip= Trip::create([
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


        //update trip with selected rider id:
        $trip->rider_id = $rider->id;
        $trip->save();

        //update rider ride status:
        $rider->on_a_ride = true;
        $rider->save();

        //get the profile of the rider:
        $profile = $rider->profile;

        //nest trip, rider and package together to get all in reponse:
        $info  = [
            'trip' => $trip,
            'rider' => $rider,
            'package' => $package,
        ];
        return $this->sendResponse($info, 'Rider located!');

                
    } catch(\Exception $e){

        return $this->sendError("Cannot locate a rider at the moment", 'Cannot locate a rider at the moment');
    }
            
            
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
                        ->where('trip_status', 'ENDED')
                        ->latest()
                        ->get();
                
                if(count($tripHistory)){
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
        $id = $user->id;
        $history = Trip::where('customer_id', $id)
                    ->latest()
                    ->get();
            
            if(count($history)){

                return $this->sendResponse($history, 'Your order history');
                
            }
            else{
                return $this->sendError('You have no history yet', 'You have no history yet');
            }
    }

    public function deliverPackage($id){
        //get the package id 
        //check in package model if package is already delivered
            //if delivered respond package already delivered
            //if not, update package status as delivered.
        //if package not delivered, change status to not delivered
        $package = Package::find($id);
        if($package == null){
            return $this->sendError("Package not found", 404);
        }

        if($package->package_status == "DELIVERED"){
            return $this->sendError("This package has already been delivered!");
        }

        $package->package_status = "DELIVERED";
        $package->save();

        return $this->sendResponse($package, "Package Delivered!");
    }

    public function packageNotDelivered($id){
        //get the package id 
        //check in package model if package is already delivered
            //if delivered respond package already delivered
            //if not, update package status as delivered.
        //if package not delivered, change status to not delivered
        $package = Package::find($id);
        if($package == null){
            return $this->sendError("Package not found", 404);
        }

        if($package->package_status == "NOT_DELIVERED"){
            return $this->sendError("This package has already been set as not delivered!");
        }

        $package->package_status = "NOT_DELIVERED";
        $package->save();

        return $this->sendResponse($package, "Package Not Delivered!");
    }


    public function getRiderLocation($tripId, $riderId, $packageId, $km,$time){

             $timeInMin = ($time * 60);
             $baseFare = 200; 
             $CalculateCost = $baseFare + ($timeInMin * $km);

            $riderLocation = Riderlocation::where('rider_id', $riderId)
                                ->where('trip_id',$tripId)->latest()
                                ->first();
            
            $package = Package::find($packageId);

            //update trip cost
            $trip = Trip::find($tripId);    
            $trip->cost_of_trip = $CalculateCost;
            $trip->save();

            $info  = [
            'riderLocation'=> $riderLocation,
            'package' => $package,
            'cost' => $CalculateCost ,
            ];

            if($riderLocation == null){
                return $this->sendError("The rider location is not yet available.", "The rider location is not yet available.");
            }

            else{

                return $this->sendResponse($info, "This is your rider's current location and your trip cost.");  
            }            
    }
    
    public function saveRiderLocation(Request $request){
        //get the ridr id
        //get latitude and longitude
        //get the trip id
        //save to the database

        $data = $request->validate([
            'latitude'=>['required', 'string', 'max:20'],
            'longitude'=>['required','string', 'max:20'],
            'trip_id' => ['required', 'string', 'max:20']
        ]);

        if(!$data){
            return $this->sendError("Something is wrong with your input", "Something is wrong with your input");
        }
        else{

            $riderlocation = RiderLocation::create([
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'rider_id' => auth()->user()->id,
                'trip_id' => $data['trip_id']
            ]);

            if($riderlocation){
                    return $this->sendResponse($riderlocation, 'Current location saved.');
                }
                else{

                    return $this->sendError('Could not save your current location.');
                }

        }
    }
    
    public function getTrip($tripId){
        $trip = Trip::find($tripId);

        if($trip == null){
            return $this->sendError("Trip does not exist");
        }else{
            $trip->get();

            return $this->sendResponse($trip, 'Trip Details.');

        }
    }


}   