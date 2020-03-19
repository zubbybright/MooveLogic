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
    Route::post('add-card','ProfileController@addBankCard');
    Route::post('add-dp','ProfileController@addProfilePic');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');

    Route::post('create-admin', 'ProfileController@createAdmin');






});
	//start, end and cancel trips;

    Route::post('start-trip/{tripId}', 'TripsController@startTrip')->middleware('auth:api');
    Route::post('end-trip/{tripId}', 'TripsController@endTrip')->middleware('auth:api');
    Route::post('cancel-trip/{tripId},{riderId}', 'TripsController@cancelTrip')->middleware('auth:api');

    //save rider current location
    Route::post('rider-location', 'TripsController@saveRiderLocation')->middleware('auth:api');

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


