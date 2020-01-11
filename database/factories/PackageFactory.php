<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Package;
use App\User;
use Faker\Generator as Faker;

$factory->define(Package::class, function (Faker $faker) {
    return [
        //

        'package_description'=> $faker->sentence,
        'package_type' => $faker->randomElement(['FRAGILE' ,'NOT_FRAGILE']),
        'customer_id'=> ($faker->randomElement(User::where('user_type', 'CUSTOMER')->get()))->id,
        'package_status' => 'PENDING'
    ];
});
