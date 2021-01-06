<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Package;
use Carbon\Carbon;

class Trip extends Model
{
    //
    protected $fillable = ['current_location', 'start_location', 'end_location','start_time', 'end_time','cost_of_trip','trip_status','recipient_name','recipient_phone_number', 'rider_id', 'package_id','who_pays','payment_method', 'moove_id','customer_id', 'package_description'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function package(){
        return $this->belongsTo(Package::class);
    }

    public function getCreatedAtAttribute($timestamp) {
        return Carbon::parse($timestamp)->format('F d, Y');
    }
}
