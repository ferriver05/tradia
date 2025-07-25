<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Fernando Rivera',
            'city_id' => 866,
            'alias' => 'admin_1',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'reputation' => 6,
        ]);

        User::create([
            'name' => 'Julio Vargas',
            'city_id' => 866,
            'alias' => 'mod_1',
            'email' => 'mod@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mod',
            'status' => 'active',
            'reputation' => 6,
        ]);

        User::create([
            'name' => 'Maria Machado',
            'city_id' => 866,
            'alias' => 'user_1',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'reputation' => 6,
        ]);
    }
}
