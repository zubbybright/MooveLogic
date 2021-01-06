<?php

namespace App\Models;

use App\Models\User;
use App\Models\Trip;
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
