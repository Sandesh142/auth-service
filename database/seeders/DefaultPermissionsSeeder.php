<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\Permission;

class DefaultPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Client Management
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'view_branches',
            'create_branches',
            'edit_branches',
            'delete_branches',
            'view_service_types',
            'create_service_types',
            'edit_service_types',
            'delete_service_types',

            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            // Role & Permission Management (Clinic-level roles)
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'assign_roles', // Permission to assign clinic roles to users

            // System-level permissions (managed by SuperAdmin)
            'manage_system_permissions', // Only SuperAdmin should have this to create/edit global permissions

            // Revenue Management
            'view_revenue',
            'create_revenue',
            'edit_revenue',
            'delete_revenue',
            'view_revenue_summary',

            // Inventory Management
            'view_medicines',
            'create_medicines',
            'edit_medicines',
            'delete_medicines',
            'view_batches',
            'create_batches',
            'edit_batches',
            'delete_batches',

            // Sales Management
            'view_sales',
            'create_sales',
            'edit_sales',
            'delete_sales',

            // NEW PERMISSION ADDED HERE
            'view_reports', // For "Reports & Analytics"
            'view_invoices', // For "Invoices"
            'view_settings', // For "Settings"
            'view_payment_gateway', // For "Payment Gateway"
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
