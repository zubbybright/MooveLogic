<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Profile extends Model implements HasMedia
{
    use InteractsWithMedia,  HasFactory;
    
    protected $fillable = ['first_name', 'last_name'];


}
