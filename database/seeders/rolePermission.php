<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class rolePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['super_admin', 'product_manager', 'user_manager','guest'];
        $permissions = ['view_dashboard', 'view_products', 'create_products','edit_products', 
        'delete_products', 'view_categories', 'create_categories','edit_categories', 
        'delete_categories', 'view_users', 'create_users', 'edit_users', 'delete_users'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        foreach ($roles as $role) {
            $roleInstance = Role::firstOrCreate(['name' => $role]);

            if ($role === 'super_admin') {
                $roleInstance->syncPermissions(Permission::all());
            } elseif ($role === 'product_manager') {
                $roleInstance->syncPermissions(['view_products','create_products','edit_products','delete_products']);
            } elseif ($role === 'user_manager'){
                $roleInstance->syncPermissions([ 'view_users', 'create_users', 'edit_users', 'delete_users']);
            } else {
                $roleInstance->syncPermissions(['view_products']);
            }
        }
    }
}