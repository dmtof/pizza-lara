<?php

namespace Database\Seeders;

use App\Models\ProductItem;
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
        ProductItem::create([
            'name' => 'Маргарита',
            'category_id' => 1,
            'description' => '30 см, высокое качество',
            'price' => 1000,
        ]);

        ProductItem::create([
            'name' => 'Баварская',
            'category_id' => 1,
            'description' => '45 см, низкое качество',
            'price' => 500,
        ]);

        ProductItem::create([
            'name' => 'Пиво Балтика',
            'category_id' => 2,
            'description' => 'Из лушчих сортов чего-то там',
            'price' => 69,
        ]);
    }
}
