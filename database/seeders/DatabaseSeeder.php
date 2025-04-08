<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create Permissions
        $permissions = ['create users', 'edit users', 'delete users', 'view dashboard']; // Add more as needed
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign all permissions to admin
        $adminRole->syncPermissions(Permission::all());

        // Create admin user
        $admin = User::firstOrCreate([
            'email' => 'admin@elite.org.afg',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('admin'),
        ]);

        $admin->assignRole($adminRole);
    }
}
