<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;



class Profile extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = ['first_name', 'last_name','password','user_id', 'card_number','card_name','cvv','expiration_year', 'expiration_month',];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }
}
