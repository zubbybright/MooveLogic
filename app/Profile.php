<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Profile extends Model implements HasMedia
{
    use HasMediaTrait;
    
    protected $fillable = ['first_name', 'last_name','password','user_id', 'card_number','card_name','cvv','expiration_year', 'expiration_month',];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
