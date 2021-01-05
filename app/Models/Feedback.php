<?php

namespace App\Models;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';


    protected $fillable = ['user_id', 'profile_id', 'feedback_description', 'feedback_type'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function profile(){
        return $this->belongsTo(Profile::class);
    }
}