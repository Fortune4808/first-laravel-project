<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear any cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $createStaff = Permission::firstOrCreate(['name' => 'CREATE STAFF', 'guard_name' => 'admin']);
        $updateStaff = Permission::firstOrCreate(['name' => 'UPDATE STAFF', 'guard_name' => 'admin']);
        $viewStaff = Permission::firstOrCreate(['name' => 'VIEW STAFF', 'guard_name' => 'admin']);
        $viewUsers = Permission::firstOrCreate(['name' => 'VIEW USERS', 'guard_name' => 'admin']);
        $updateUsers = Permission::firstOrCreate(['name' => 'UPDATE USERS', 'guard_name' => 'admin']);
        $viewSlot = Permission::firstOrCreate(['name' => 'VIEW SLOT', 'guard_name' => 'admin']);
        $addSlot = Permission::firstOrCreate(['name' => 'ADD SLOT', 'guard_name' => 'admin']);
        $updateSlot = Permission::firstOrCreate(['name' => 'UPDATE SLOT', 'guard_name' => 'admin']);
        $deleteSlot = Permission::firstOrCreate(['name' => 'DELETE SLOT', 'guard_name' => 'admin']);
        $viewLocation = Permission::firstOrCreate(['name' => 'VIEW LOCATION', 'guard_name' => 'admin']);
        $updateLocation = Permission::firstOrCreate(['name' => 'UPDATE LOCATION', 'guard_name' => 'admin']);
        $addLocation = Permission::firstOrCreate(['name' => 'ADD LOCATION', 'guard_name' => 'admin']);
        $deleteLocation = Permission::firstOrCreate(['name' => 'DELETE LOCATION', 'guard_name' => 'admin']);
        $viewRole = Permission::firstOrCreate(['name' => 'VIEW ROLE', 'guard_name' => 'admin']);
        $updateRole = Permission::firstOrCreate(['name' => 'UPDATE ROLE', 'guard_name' => 'admin']);
        $addRole = Permission::firstOrCreate(['name' => 'ADD ROLE', 'guard_name' => 'admin']);
        $viewPermission = Permission::firstOrCreate(['name' => 'VIEW PERMISSION', 'guard_name' => 'admin']);
        $addPermission = Permission::firstOrCreate(['name' => 'ADD PERMISSION', 'guard_name' => 'admin']);

        $superAdmin = Role::firstOrCreate(['name' => 'SUPER ADMIN', 'guard_name' => 'admin']);
        $admin = Role::firstOrCreate(['name' => 'ADMIN', 'guard_name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'STAFF', 'guard_name' => 'admin']);
   
        $superAdmin->givePermissionTo(Permission::all());
        $admin->givePermissionTo([
            $createStaff, $updateStaff, $viewStaff, $viewUsers, $updateUsers, $viewSlot, $addSlot,
            $updateSlot, $deleteSlot, $viewLocation, $updateLocation, $addLocation, $deleteLocation, 
            $viewRole, $updateRole, $addRole, $viewPermission
        ]);
        $staff->givePermissionTo([
            $viewUsers, $updateUsers, $viewSlot, $addSlot, $updateSlot, $viewLocation,
            $updateLocation, $addLocation
        ]);
    }
}
