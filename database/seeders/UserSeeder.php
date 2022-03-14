<?php

namespace Database\Seeders;

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

        User::factory()->create([
            'name' => 'Test Name',
            'email' => 'test@email.com',
            'password' => '$2y$10$QxpDinw91KCZLNXYW7LPPetfFXVouKfHpCo9eKTomAc4c7v536gs.'
        ]);

        User::factory()->create([
            'name' => 'Second Test Name',
            'email' => 'secondTestEmail@email.com',
            'password' => '$2y$10$QxpDinw91KCZLNXYW7LPPetfFXVouKfHpCo9eKTomAc4c7v536gs.'

        ]);
    }
}
