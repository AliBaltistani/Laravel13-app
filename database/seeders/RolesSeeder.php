<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = ['super_admin', 'admin', 'manager', 'editor', 'support'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create basic permissions
        $permissions = [
            'manage_products',
            'manage_categories',
            'manage_brands',
            'manage_orders',
            'manage_customers',
            'manage_reviews',
            'manage_coupons',
            'manage_flash_sales',
            'manage_blog',
            'manage_pages',
            'manage_banners',
            'manage_sliders',
            'manage_settings',
            'manage_shipping',
            'manage_newsletter',
            'manage_reports',
            'manage_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to super_admin
        $superAdmin = Role::findByName('super_admin');
        $superAdmin->givePermissionTo(Permission::all());

        // Assign most permissions to admin (except manage_roles)
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::where('name', '!=', 'manage_roles')->get());

        // Manager: orders, customers, products, reports
        $manager = Role::findByName('manager');
        $manager->givePermissionTo([
            'manage_products', 'manage_categories', 'manage_brands',
            'manage_orders', 'manage_customers', 'manage_coupons',
            'manage_flash_sales', 'manage_shipping', 'manage_reports',
        ]);

        // Editor: content management
        $editor = Role::findByName('editor');
        $editor->givePermissionTo([
            'manage_products', 'manage_categories', 'manage_brands',
            'manage_blog', 'manage_pages', 'manage_banners', 'manage_sliders',
            'manage_reviews',
        ]);

        // Support: orders, customers, reviews
        $support = Role::findByName('support');
        $support->givePermissionTo([
            'manage_orders', 'manage_customers', 'manage_reviews',
        ]);
    }
}
