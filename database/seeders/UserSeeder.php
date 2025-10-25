<?php

namespace Database\Seeders;

use Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/user.json')), true);

        foreach ($data as $item) {
            User::create([
                'name' => $item['name'],
                'email' => $item['email'],
                'phone' => $item['phone'],
                'password' => bcrypt($item['password']),
                'email_verified_at' => $item['email_verified_at'],
                'verification_code' => $item['verification_code'],
            ]);
        }
    }
}
