<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Permissions
        $permissions = [
            'manage projects',
            'manage boards',
            'manage tasks',
            'comment tasks',
            'view only'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $contributor = Role::firstOrCreate(['name' => 'contributor']);
        $viewer = Role::firstOrCreate(['name' => 'viewer']);

        // Role → Permissions
        $admin->givePermissionTo(Permission::all());
        $manager->givePermissionTo(['manage projects','manage boards','manage tasks','comment tasks']);
        $contributor->givePermissionTo(['manage tasks','comment tasks']);
        $viewer->givePermissionTo(['view only']);

        // Users
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole($admin);

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
            ]
        );
        $managerUser->assignRole($manager);

        $contributorUser = User::firstOrCreate(
            ['email' => 'contributor@example.com'],
            [
                'name' => 'Contributor User',
                'password' => Hash::make('password'),
            ]
        );
        $contributorUser->assignRole($contributor);

        $viewerUser = User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => Hash::make('password'),
            ]
        );
        $viewerUser->assignRole($viewer);
    }
}
