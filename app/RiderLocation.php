<?php

namespace App;

use App\User;
use App\Trip;
use Illuminate\Database\Eloquent\Model;

class RiderLocation extends Model
{
    //
    protected $fillable = ['latitude','longitude', 'rider_id', 'trip_id'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' =>'float'
    ];

   	public function user(){
        return $this->belongsTo(User::class);
    }
}
