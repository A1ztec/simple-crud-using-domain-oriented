<?php

namespace Database\Seeders;

use Domain\Order\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/order.json')), true);

        foreach ($data as $item) {
            Order::create([
                'uuid' => $item['uuid'],
                'user_id' => $item['user_id'],
                'total_amount' => $item['total_amount'],
                'status' => $item['status'],
                'shipping_address' => $item['shipping_address'],
                'paid_at' => $item['paid_at'],
            ]);
        }
    }
}
