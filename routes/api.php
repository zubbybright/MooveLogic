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

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@reset')->name('password.reset');



});
	//start, end and cancel trips;
    
    Route::post('start-trip/{tripId}', 'TripsController@startTrip');
    Route::post('end-trip/{tripId}', 'TripsController@endTrip');
    Route::post('cancel-trip/{tripId},{riderId}', 'TripsController@cancelTrip');
    
    //save rider current location
    Route::post('rider-location', 'TripsController@saveRiderLocation');

    //get rider current location
    Route::get('getRiderLocation/{tripId},{riderId},{packageId}','TripsController@getRiderLocation');

    //delivered or not delivered package:
    Route::post('delivered/{id}', 'TripsController@deliverPackage');
    Route::post('not-delivered/{id}', 'TripsController@packageNotDelivered');

   //make a moove request, contact a rider:
    Route::post('request-rider', 'TripsController@findRider');

    //rider active ride:
    Route::post('active-ride', 'TripsController@findActiveTrip');

    //rider trip history:
    Route::get('trip-history', 'TripsController@riderTripHistory');
    //payment
    Route::post('payment-method', 'PaymentController@makePayment');
    //calculate cost:
    Route::post('cost', 'TripsController@calculateCost');
    //customer order history;
    Route::get('customer-history', 'TripsController@customerOrderHistory');

    //submit feedback:
    Route::post('feedback', 'ProfileController@feedback');

    //get trip
    Route::get('get-trip/{tripId}', 'TripsController@getTrip');
