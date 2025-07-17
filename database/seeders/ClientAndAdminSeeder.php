<?php

namespace Database\Seeders;

    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use App\Models\Admin\Client;
    use App\Models\User;
    use App\Models\Role;
    use App\Models\Admin\Permission;
    use App\Models\Admin\Branch;
    use App\Models\Admin\ClientServiceType;
    use Illuminate\Support\Facades\Hash;

    class ClientAndAdminSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // 1. Create a Sample Client
            $client = Client::firstOrCreate(
                ['contact_email' => 'admin@clinic.com'],
                [
                    'name' => 'Sample Clinic Pharma',
                    'phone' => '123-456-7890',
                    'address' => '123 Clinic St',
                    'city' => 'Healthville',
                    'state' => 'CA',
                    'zip_code' => '90210',
                    'country' => 'USA',
                    'description' => 'A sample clinic for demonstration purposes.',
                    'status' => 'active',
                ]
            );

            // 2. Create an Admin User for the Sample Client
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@clinic.com'],
                [
                    'name' => 'Clinic Admin',
                    'password' => Hash::make('password'),
                    'client_id' => $client->id,
                    'role_id' => '2', // System-level role 'admin'
                    'email_verified_at' => now(),
                ]
            );

            // 3. Create Clinic-Specific Roles for this Client
            $pharmacistRole = Role::firstOrCreate(
                ['client_id' => $client->id, 'name' => 'Pharmacist'],
                ['guard_name' => 'web']
            );
            $receptionistRole = Role::firstOrCreate(
                ['client_id' => $client->id, 'name' => 'Receptionist'],
                ['guard_name' => 'web']
            );

            // 4. Assign Permissions to Clinic-Specific Roles
            // Get all relevant permissions
            $allPermissions = Permission::all();

            // Example: Pharmacist role permissions
            $pharmacistPermissions = $allPermissions->whereIn('name', [
                'view_medicines',
                'create_sales',
                'view_sales',
                'view_revenue',
                'create_revenue',
                'view_reports', // NEW
                'view_invoices', // NEW
            ])->pluck('id')->toArray();
            if ($pharmacistRole) {
                $pharmacistRole->permissions()->syncWithoutDetaching($pharmacistPermissions);
            }

            // Example: Receptionist role permissions
            $receptionistPermissions = $allPermissions->whereIn('name', [
                'view_sales',
                'create_sales',
                'view_revenue',
                'view_reports', // NEW
            ])->pluck('id')->toArray();
            if ($receptionistRole) {
                $receptionistRole->permissions()->syncWithoutDetaching($receptionistPermissions);
            }

            // For the Clinic Admin, they usually get broader permissions.
            // If you want the Clinic Admin's system role 'admin' to have specific clinic-level permissions,
            // you can assign them here. Or, rely on their 'admin' system role to grant broad access.
            // For now, let's ensure the admin user also has some of these new permissions directly via a role.
            if ($adminUser && $pharmacistRole) {
                $adminUser->roles()->syncWithoutDetaching([$pharmacistRole->id]);
            }


            // 6. Create a Staff User for the Sample Client and assign a clinic role
            $staffUser = User::firstOrCreate(
                ['email' => 'staff@clinic.com'],
                [
                    'name' => 'Clinic Staff',
                    'password' => Hash::make('password'),
                    'client_id' => $client->id,
                    'role_id' => '3',
                    'role' => 'staff', // System-level role 'staff'
                    'email_verified_at' => now(),
                ]
            );

            if ($staffUser && $receptionistRole) {
                $staffUser->roles()->syncWithoutDetaching([$receptionistRole->id]);
            }

            // 7. Create some sample branches for the client
            Branch::firstOrCreate(
                ['client_id' => $client->id, 'name' => 'Main Clinic Branch'],
                [
                    'address' => '123 Main St',
                    'city' => 'Healthville',
                    'state' => 'CA',
                    'zip_code' => '90210',
                    'phone' => '111-222-3333',
                    'contact_person' => 'Jane Doe',
                    'status' => 'active',
                ]
            );
            Branch::firstOrCreate(
                ['client_id' => $client->id, 'name' => 'Downtown Pharmacy'],
                [
                    'address' => '456 Oak Ave',
                    'city' => 'Healthville',
                    'state' => 'CA',
                    'zip_code' => '90211',
                    'phone' => '444-555-6666',
                    'contact_person' => 'John Smith',
                    'status' => 'active',
                ]
            );

            // 8. Create some sample client service types
            ClientServiceType::firstOrCreate(
                ['client_id' => $client->id, 'name' => 'Pharmacy Sales'],
                ['description' => 'Sales of medicines and other pharmacy products.', 'default_price' => 0.00, 'is_active' => true]
            );
            ClientServiceType::firstOrCreate(
                ['client_id' => $client->id, 'name' => 'Doctor Consultation'],
                ['description' => 'Consultation services provided by doctors.', 'default_price' => 500.00, 'is_active' => true]
            );
        }
    }
    