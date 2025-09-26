<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles (agar na ho to ban jaye)
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'member']);
    }
}
