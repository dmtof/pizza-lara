<?php

namespace Database\Seeders;

use App\Models\Cart;
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
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@pizza.ru',
            'password' => Hash::make('admin'),
            'role' => '1',
        ]);
        $admin->remember_token = $admin->createToken('admin_token')->plainTextToken;
        $admin->save();

        Cart::create([
            'cart_id' => $admin->id,
        ]);

        User::create([
            'name' => 'Guest',
            'email' => 'guest@pizza.ru',
            'password' => Hash::make('guest'),
            'role' => '0',
        ]);
    }
}
