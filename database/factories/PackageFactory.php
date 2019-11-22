<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Package;
use Faker\Generator as Faker;

$factory->define(Package::class, function (Faker $faker) {
    return [
        //

        'package_description'=> $faker->sentence,
        'package_type' => $faker->randomElement(['FRAGILE' ,'NOT_FRAGILE']),
        'size' => $faker->text, 
        'weight'=>$faker->randomDigit,
        'customer_id'=>$faker->randomDigit,
    ];
});
