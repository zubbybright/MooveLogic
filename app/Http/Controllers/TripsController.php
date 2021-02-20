<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RiderLocation;
use App\Http\Controllers\BaseController;
class TripsController extends BaseController
{
    //

    public function startTrip($tripId)
    {
        //locate the trip
        //check the current status of the trip
        // if in progress return already started
        //update status to in progress

        //how do I update the status of the package to enroute?
        //get the id of package on trip
        //find it on package model
        //update status to enroute
        //pending o
        //accepted 1
        //pickup 2
        //paid 3
        //enroute 4
        //

        $trip = Trip::find($tripId);
        if ($trip == null) {
            return $this->sendError("Trip does not exist", "Trip does not exist");
        }

        if ($trip->trip_status > 0) {
            return $this->sendError("Trip already started!", "Trip already started!");
        } else {
            $trip->trip_status = 'IN_PROGRESS';
            $trip->save();

            return $this->sendResponse($trip, "Trip started");
        }

        return $this->sendError("Cannot start trip", "Cannot start trip");
    }

    public function endTrip($tripId, $riderId)
    {
        //locate the trip
        //check the current status of the trip
        // if in progress return already started
        //update status to in progress

        //how do I update the status of the package to enroute?
        //get the id of package on trip
        //find it on package model
        //update status to enroute


        $trip = Trip::where('id', $tripId)->first();
        if ($trip == null) {
            return $this->sendError("Trip does not exist", "Trip does not exist");
        }
        if ($trip->trip_status == "ENDED") {
            return $this->sendError("Trip already ended!", "Trip already ended!");
        } else {
            $trip->trip_status = 'ENDED';
            $trip->save();

            $rider = User::find($riderId);
            $rider->on_a_ride = false;
            $rider->save();

            return $this->sendResponse($trip, "Trip ended.");
        }

        return $this->sendError("Cannot start trip", "Cannot start trip");
    }

    public function cancelTrip($tripId, $riderId)
    {
        $trip = Trip::find($tripId);

        if ($trip == null) {
            return $this->sendError("Trip does not exist");
        }

        if ($trip->trip_status == "CANCELLED") {
            return $this->sendError("Trip has already been cancelled!");
        } else {
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


   
    public function calculateCost(Request $request)
    {
        // $timeInMin = ($data['time'] * 60);
        // $baseFare = 200;
        // $calculateCost = $baseFare + ($timeInMin * $data['km']);
        $calculateCost = 1500;


        return $this->sendResponse($calculateCost, 'Cost of trip');
    }



    public function findRider(Request $request)
    {
        $data = $request->validate([
            'start_location' => ['nullable', 'max:100'],
            'end_location' => ['required', 'string', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:100'],
            'recipient_phone_number' => ['required', 'string', 'max:14'],
            'package_description' => ['string', 'max:255'],
            'latitude' => ['required', 'max:20'],
            'longitude' => ['required', 'max:20'],
        ]);

        $trip_cost = 1500;

        $rider =  User::rider()->where('on_a_ride', 0)->inRandomOrder()->first();
        // ->whereBetween('latitude', [$data['latitude']- (10 * 0.018), $data['latitude']+ (10 * 0.018)])
        // ->whereBetween('longitude', [$data['longitude']- (10 * 0.018), $data['longitude']+ (10 * 0.018)])

        if (!$rider) {
            return $this->sendError("No rider available at the moment. Please try again later", "No rider available at the moment");
        }

        //create trip:
        $trip = Trip::create([
            'start_location' => $data['start_location'],
            'end_location' => $data['end_location'],
            'cost_of_trip' => $trip_cost,
            'trip_status' => 3,
            'package_description' => $data['package_description'],
            'recipient_name' => $data['recipient_name'],
            'recipient_phone_number' => $data['recipient_phone_number'],
            'payment_method' => 0,

            'customer_id' => auth()->user()->id,
            'rider_id' => $rider->id,
        ]);


        //update rider ride status:
        $rider->on_a_ride = true;
        $rider->save();
        return $this->sendResponse($trip, 'Rider located!');
    }

    public function findActiveTrip()
    {
        $rider = auth()->user();
        $trip = Trip::where('rider_id', $rider->id)
            ->where('trip_status', '<',  6)->latest()->first();

        if(!$trip)
        {
            return $this->sendResponse(false,  'No active trip');
        }   
        
        if ($trip) {
            $trip->load('customer');
            return $this->sendResponse($trip, 'This is your active ride');
        } else {

            return $this->sendError('Something went wrong please contact admin');
        }
    }

    public function riderTripHistory()
    {

        //get te rider id
        //check ended trips with rider id
        //get

        $rider = auth()->user();
        $tripHistory = Trip::where('rider_id', $rider->id)
            ->where('trip_status', 'ENDED')
            ->latest()
            ->get();

        if (count($tripHistory)) {
            return $this->sendResponse($tripHistory, 'Your trip history');
        } else {
            return $this->sendError('You have no history yet', 'You have no history yet');
        }
    }

    public function customerOrderHistory()
    {
        //get te user id
        //check trips with customer id
        //get
        $user = auth()->user();
        $history = Trip::where('customer_id', $user->id)
            ->latest()
            ->get();

        return $this->sendResponse($history, 'Your moove history.');
    }

    public function deliverPackage($packageId)
    {
        //get the package id
        //check in package model if package is already delivered
        //if delivered respond package already delivered
        //if not, update package status as delivered.
        //if package not delivered, change status to not delivered
        $package = Package::find($packageId);
        if ($package == null) {
            return $this->sendError("Package not found", "Package not found");
        }

        if ($package->package_status == "DELIVERED") {
            return $this->sendError("This package has already been delivered!");
        }

        $package->package_status = "DELIVERED";
        $package->save();

        return $this->sendResponse($package, "Package Delivered!");
    }

    public function packageNotDelivered($packageId)
    {
        //get the package id
        //check in package model if package is already delivered
        //if delivered respond package already delivered
        //if not, update package status as delivered.
        //if package not delivered, change status to not delivered
        $package = Package::find($packageId);
        if ($package == null) {
            return $this->sendError("Package not found", 404);
        }

        if ($package->package_status == "NOT_DELIVERED") {
            return $this->sendError("This package has already been set as not delivered!");
        }

        $package->package_status = "NOT_DELIVERED";
        $package->save();

        return $this->sendResponse($package, "Package Not Delivered!");
    }


    public function getRiderLocation($riderId, $tripId)
    {
        $riderLocation = RiderLocation::where('rider_id', $riderId)
            ->where('trip_id', $tripId)->latest()
            ->first();
        $trip = Trip::select('trip_status')->where('id', $tripId)->first();

        $info  = [
            'trip' => $trip,
            'riderLocation' => $riderLocation
        ];
        return $this->sendResponse($info, "This is your rider's current location and trip status.");
    }

    public function saveRiderLocation($tripId, $riderId, $lat, $long)
    {
        //get the ridr id
        //get latitude and longitude
        //get the trip id
        //save to the database

        $riderlocation = RiderLocation::create([
            'latitude' => $lat,
            'longitude' => $long,
            'rider_id' => $riderId,
            'trip_id' => $tripId
        ]);

        if ($riderlocation) {
            return $this->sendResponse($riderlocation, 'Current location saved.');
        } else {

            return $this->sendError('Could not save your current location.');
        }
    }

    public function updateLocation(Request $request)
    {
        //get the ridr id
        //get latitude and longitude
        //save to the database

        $data = $request->validate([
            'latitude' => ['required', 'max:20'],
            'longitude' => ['required', 'max:20'],
        ]);

        if (!$data) {
            return $this->sendError("Something is wrong with your input", "Something is wrong with your input");
        } else {

            $user = auth()->user();
            $userId = $user->id;

            $location = User::where('id', $userId)
                ->update([
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude']
                ]);

            if ($location) {
                return $this->sendResponse($user, 'Current location saved.');
            } else {

                return $this->sendError('Could not save your current location.');
            }
        }
    }


    public function getTrip($tripId)
    {
        $trip = Trip::find($tripId);

        if ($trip == null) {
            return $this->sendError("Trip does not exist");
        } else {
            return $this->sendResponse($trip, 'Trip Details.');
        }
    }
}
