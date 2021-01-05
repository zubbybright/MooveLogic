<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Trip;

class Package extends Model
{
    //
    protected $fillable = ['package_description', 'package_type', 'size', 'weight', 'customer_id', 'package_status', 'trip_id'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function trip(){
    	return $this->hasOne(Trip::class);
    }
}
