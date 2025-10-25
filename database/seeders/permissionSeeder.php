<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domain\User\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = [
            'admin',
            'user',
        ];

        $permissions = [
            'create_order',
        ];


        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }


        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        $userRole = Role::where('name', 'user')->first();
        $userRole->givePermissionTo('create_order');

        User::where('id', 1)->first()?->assignRole('user');
        User::where('id', 2)->first()?->assignRole('admin');
    }
}
