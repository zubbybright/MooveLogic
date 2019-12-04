<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Profile extends Model
{
    //
    protected $fillable = ['first_name', 'last_name','email','password','user_id', 'card_number','card_name','cvv','expiry_date'];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
