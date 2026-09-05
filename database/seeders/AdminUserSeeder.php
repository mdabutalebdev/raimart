<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@raimart.com'],
            [
                'name' => 'Raimart Admin',
                'password' => Hash::make('Admin@12345'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
