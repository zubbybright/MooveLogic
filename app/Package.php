<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Trip;

class Package extends Model
{
    //
    protected $fillable = ['description', 'package_type', 'size', 'weight', 'customer_id', 'package_status', 'trip_id'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function trip(){
    	return $this->hasOne(Trip::class);
    }
}
