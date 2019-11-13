<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Profile;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        //
        'first_name'=> $faker->firstName,
        'last_name'=> $faker->lastName,
        'date_of_birth' => $faker->dateTimeThisDecade,
    ];
});
