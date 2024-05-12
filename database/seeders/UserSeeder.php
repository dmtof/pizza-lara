<?php

namespace Database\Seeders;

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
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@pizza.ru',
            'password' => Hash::make('admin'),
            'role' => '1',
        ]);

        DB::table('users')->insert([
            'name' => 'Guest',
            'email' => 'guest@pizza.ru',
            'password' => Hash::make('guest'),
            'role' => '0',
        ]);
    }
}
