<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['manager', 'keeper', 'customer'];
        $permissions = ['create role', 'edit role', 'delete role', 'view role'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $managerRole = Role::where('name', 'manager')->first();
        $managerRole?->givePermissionTo($permissions);

        $users = [
            [
                'role' => 'manager',
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'phone' => '081000000001',
                'photo' => 'users/manager-user.jpg',
            ],
            [
                'role' => 'keeper',
                'name' => 'Keeper User',
                'email' => 'keeper@example.com',
                'phone' => '081000000002',
                'photo' => 'users/keeper-user.jpg',
            ],
            [
                'role' => 'customer',
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'phone' => '081000000003',
                'photo' => 'users/customer-user.jpg',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'phone' => $userData['phone'],
                    'photo' => $userData['photo'],
                    'password' => Hash::make('password123'),
                ]
            );

            $user->syncRoles([$userData['role']]);
        }
    }
}
