<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Package;

class Trip extends Model
{
    //
    protected $fillable = ['current_location', 'start_location', 'end_location','start_time', 'end_time','cost_of_trip','trip_status','recipient_name','recipient_phone_number', 'rider_id', 'package_id','who_pays','payment_method', 'moove_id','customer_id'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }
}
