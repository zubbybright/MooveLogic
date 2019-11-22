<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Profile extends Model
{
    //
    protected $fillable = ['first_name', 'last_name','email','password','user_id'];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
