<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    protected $fillable = [
        'first_name', 'email', 'password','last_name','username', 'phone_number'
    ];
}
