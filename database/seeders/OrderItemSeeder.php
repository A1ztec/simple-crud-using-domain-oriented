<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domain\Order\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/orderitem.json')), true);

        foreach ($data as $item) {
            OrderItem::create([
                'id' => $item['id'],
                'order_uuid' => $item['order_uuid'],
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }
}
