<?php

namespace App\Models;

use App\Models\Profile;
use App\Models\Trip;
use App\Models\Package;
use App\Models\Feedback;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\PasswordResetNotification;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number', 'email', 'password','facebook_id','user_type', 'current_location','on_a_ride', 'token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'latitude' => 'float',
        'longitude' =>'float'
    ];

    public function packages(){
        return $this->hasMany(Package::class);
    }

    public function trips(){
        return $this->hasMany(Trip::class);
    }

    public function riderLocation(){
        return $this->hasMany(RiderLocation::class);
    }

    public function feedbacks(){
        return $this->hasMany(Feedback::class);
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}