<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => Role::ROLE_ADMIN,
            'guard_name' => 'api',
        ]);

        Role::firstOrCreate([
            'name' => Role::ROLE_USER,
            'guard_name' => 'api',
        ]);

        Role::firstOrCreate([
            'name' => Role::ROLE_CUSTOMER,
            'guard_name' => 'api',
        ]);

        Role::firstOrCreate([
            'name' => Role::ROLE_SUPER_ADMIN,
            'guard_name' => 'api',
        ]);

        Role::firstOrCreate([
            'name' => Role::ROLE_STAFF,
            'guard_name' => 'api',
        ]);
    }
}
