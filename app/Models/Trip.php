<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['current_location', 'start_location', 'end_location','start_time', 'end_time','cost_of_trip','trip_status','recipient_name','recipient_phone_number', 'rider_id', 'payment_method', 'customer_id', 'package_description'];
    protected $appends = [
        'moove_name'
    ];

    public function customer(){
        return $this->belongsTo(User::class);
    }

    public function rider(){
        return $this->belongsTo(User::class);
    }

    public function getMooveNameAttribute()
    {
        return  "MOOV".$this->attributes['id'];
    }

    public function getCreatedAtAttribute($timestamp) {
        return Carbon::parse($timestamp)->format('F d, Y');
    }

    public function getTripStatusAttribute($status)
    {
        $dict = array( 0 => "PLACED", 1 => "RIDER_ASSIGNED", 2, "RIDER_ACCEPTED", "PACKAGE_PICKED_UP", 3 => "PAYMENT_MADE", 4 => "DELIVERING", 5 => "DELIVERED", 6 => "CANCELLED" );
        return $dict[$status];
    }
}
