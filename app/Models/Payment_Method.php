<?php

namespace App\Models;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    //

    protected $fillable = ['card_option','cash_on_delivery'];
}
