<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition()
    {
        return [
            "current_location" => $this->faker->sentence(),
            "start_location" => $this->faker->sentence(),
            "end_location" => $this->faker->sentence(),
            "start_time" => now(),
            "end_time" => now(),
            "cost_of_trip" => $this->faker->randomNumber(),
            "trip_status" =>  $this->faker->randomElement([0,1,2,3,4]),
            "package_description" => $this->faker->sentence(),
            "recipient_name" => $this->faker->name(),
            "recipient_phone_number" => $this->faker->phoneNumber,
            "payment_method" =>  $this->faker->randomElement([0,1]),
            
            "rider_id" =>  User::factory()->rider(),
            "customer_id" =>  User::factory()->customer(),
        ];
    }
}