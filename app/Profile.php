<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Profile extends Model
{
    //
    protected $fillable = ['first_name', 'last_name','profile_pic','password','user_id', 'card_number','card_name','cvv','expiration_year', 'expiration_month'];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
