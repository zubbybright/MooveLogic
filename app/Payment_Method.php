<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

class Payment_Method extends Model
{
    //

    protected $fillable = ['card_option', 'paypal','moove_wallet','cash_on_delivery'];
}
