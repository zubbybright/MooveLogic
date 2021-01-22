<?php
namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::factory()
            ->count(10)
            ->state(new Sequence(
                ['user_type' => 0],
                ['user_type' => 1],
            ))
            ->create();
    }
}
