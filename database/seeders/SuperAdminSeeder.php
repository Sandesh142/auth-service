<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\SuperAdmin\SuperAdmin;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         if (!SuperAdmin::where('email', 'superadmin@example.com')->exists()) {
            SuperAdmin::create([
                'name' => 'Super Admin',
                'email' => 'shivaresandesh@gmail.com',
                'password' => Hash::make('password'), // Default password
                'status' => '1', // Or whatever status your table expects
                'role_id' => '1',
                'provider_name' => null,
                'provider_id' => null,
                'avatar' => null,
                'first_name' => 'Super',
                'last_name' => 'Admin',
            ]);

            $this->command->info('✅ Super Admin created successfully!');
        } else {
            $this->command->info('ℹ️ Super Admin already exists. Skipping...');
        }
    }
}
