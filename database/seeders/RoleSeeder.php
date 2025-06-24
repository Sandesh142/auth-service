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
        $roles = [
            [
                'name' => 'superadmin',
                'description' => 'Full system access',
                'status' => 1,
                'is_two_factor_enabled' => false,
                'two_factor_channel' => null
            ],
            [
                'name' => 'admin',
                'description' => 'Manages client-specific data',
                'status' => 1,
                'is_two_factor_enabled' => false,
                'two_factor_channel' => null
            ],
            [
                'name' => 'staff',
                'description' => 'Day-to-day operational role',
                'status' => 1,
                'is_two_factor_enabled' => false,
                'two_factor_channel' => null
            ],
            [
                'name' => 'client',
                'description' => 'Access to reports and payments',
                'status' => 1,
                'is_two_factor_enabled' => false,
                'two_factor_channel' => null
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
