<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domain\Product\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/product.json')), true);

        foreach ($data as $item) {
            Product::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }
    }
}
