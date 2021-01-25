<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['current_location', 'start_location', 'end_location', 'start_time', 'end_time', 'cost_of_trip', 'trip_status', 'recipient_name', 'recipient_phone_number', 'rider_id', 'payment_method', 'customer_id', 'package_description'];
    protected $appends = [
        'moove_name',
        'display_status',
        'can_track',
    ];

    const STATES = array(0 => "PLACED", 1 => "RIDER_ASSIGNED", 2 => "RIDER_ACCEPTED", 3 => "PAYMENT_MADE", 4 => "PACKAGE_PICKED_UP", 5 => "DELIVERING", 6 => "DELIVERED", 6 => "CANCELLED");

    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function rider()
    {
        return $this->belongsTo(User::class);
    }

    public function getMooveNameAttribute()
    {
        return  "MOOV" . $this->attributes['id'];
    }

    public function getCreatedAtAttribute($timestamp)
    {
        return Carbon::parse($timestamp)->format('F d, Y');
    }

    public function getTripStatusAttribute($status)
    {
        return self::STATES[$status];
    }

    public function getDisplayStatusAttribute()
    {
        $i = array(0,1,2,3,4);
        if (in_array($this->attributes['trip_status'], $i)) {
            return "PENDING";
        }
        else{
            return $this->getTripStatusAttribute($this->attributes['trip_status']);
        }
    }

    public function getCanTrackAttribute()
    {
        return $this->attributes['trip_status'] == 5;
    }
}
