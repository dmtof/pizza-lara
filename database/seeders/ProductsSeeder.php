<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_items')->insert([
            'name' => 'Маргарита',
            'description' => '30 см, высокое качество',
            'price' => 1000,
        ]);

        DB::table('product_items')->insert([
            'name' => 'Баварская',
            'description' => '45 см, низкое качество',
            'price' => 500,
        ]);
    }
}
