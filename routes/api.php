<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

	//User and Rider functionalities;

    Route::post('register-rider', 'Auth\RiderController@register');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('logout', 'Auth\LogoutController@logout');
    Route::post('refresh', 'Auth\LoginController@refresh');
    Route::post('update-password', 'ProfileController@updatePassword');
    Route::post('add-card','Auth\CreditCardRequest@rules')->middleware('auth:api');
    Route::post('add-dp','ProfileController@addProfilePic');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendEmail');
    Route::post('resend/{email}', 'Auth\ForgotPasswordController@resendEmail');
    Route::post('password/reset/{otp}', 'Auth\ResetPasswordController@reset');
    Route::post('token/validate','Auth\ResetPasswordController@validateToken' );
    Route::post('register/validate','Auth\RegisterController@checkToken' );
    Route::post('create-admin', 'ProfileController@createAdmin');






});
	//start, end and cancel trips;

    Route::post('start-trip/{tripId}', 'TripsController@startTrip');
    Route::post('end-trip/{tripId}/{riderId}', 'TripsController@endTrip');
    Route::post('cancel-trip/{tripId}/{riderId}', 'TripsController@cancelTrip');

    //save rider current location
    Route::post('rider/location/{tripId}/{riderId}/{latitude}/{longitude}', 'TripsController@saveRiderLocation');
    Route::post('update-location', 'TripsController@updateLocation')->middleware('auth:api');


    //get rider current location
    Route::get('getRiderLocation/{tripId}/{riderId}/{packageId}/{km}/{time}','TripsController@getRiderLocation')->middleware('auth:api');

    //delivered or not delivered package:
    Route::post('delivered/{packageId}', 'TripsController@deliverPackage')->middleware('auth:api');
    Route::post('not-delivered/{packageId}', 'TripsController@packageNotDelivered')->middleware('auth:api');

   //make a moove request, contact a rider:
    Route::post('request-rider', 'TripsController@findRider')->middleware('auth:api');

    //rider active ride:
    Route::get('active-ride', 'TripsController@findActiveTrip')->middleware('auth:api');

    //rider trip history:
    Route::get('trip-history', 'TripsController@riderTripHistory')->middleware('auth:api');
    //payment
    // Route::post('payment-method', 'PaymentController@makePayment');
    //calculate cost:
    Route::post('cost', 'TripsController@calculateCost');
    //customer order history;
    Route::get('customer-history', 'TripsController@customerOrderHistory')->middleware('auth:api');

    //submit feedback:
    Route::post('feedback', 'ProfileController@feedback');

    //get trip
    Route::get('get-trip/{tripId}', 'TripsController@getTrip');

    //get profile
    Route::get('profile', 'ProfileController@getProfile');


