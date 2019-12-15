<?php

namespace App;

use App\User;

use Illuminate\Database\Eloquent\Model;

class MooveRequest extends Model
{
    //
    protected $fillable = ['recipient_name','recipient_phone_number','who_pays', 'customer_id', 'package_description', 'delivery_location', 'pick_up_location', 'cost_of_trip', 'payment_method'];

    public function user(){
        return $this->belongsTo(User::class);
    } 
}
