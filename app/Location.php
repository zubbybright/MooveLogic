<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $fillable = [
        'country', 'state', 'city','local_govt','full_address' ];
}
