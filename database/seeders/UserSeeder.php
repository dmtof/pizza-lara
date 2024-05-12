<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@pizza.ru',
            'password' => Hash::make('admin'),
            'role' => '1',
        ]);

        User::create([
            'name' => 'Guest 1',
            'email' => 'guest@pizza.ru',
            'password' => Hash::make('guest'),
            'role' => '0',
        ]);
    }
}
