<?php

namespace App;

use App\Profile;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    //

    protected $fillable = ['card_option','cash_on_delivery'];
}
