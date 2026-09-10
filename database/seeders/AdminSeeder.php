<?php

/*
|==========================================================================
| database/seeders/AdminSeeder.php
|==========================================================================
*/
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cinemax.co.tz'],
            [
                'name'     => 'Cinemax Admin',
                'email'    => 'admin@cinemax.co.tz',
                'password' => Hash::make('cinemax@2025!'), // Change this!
                'is_admin' => true,
            ]
        );

        $this->command->info('Admin created: admin@cinemax.co.tz / cinemax@2025!');
        $this->command->warn('IMPORTANT: Change the password after first login!');
    }
}
