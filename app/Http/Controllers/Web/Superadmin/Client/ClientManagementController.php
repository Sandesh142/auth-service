<?php

namespace App\Http\Controllers\web\Superadmin\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Client;
use App\Models\Admin\Branch;
use App\Models\Admin\ClientServiceType;
use App\Models\User;
use App\Models\SuperAdmin\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClientManagementController extends Controller
{
    // public function __construct()
    // {
    //     // Middleware for SuperAdmin routes: Ensures only SuperAdmins can access these methods.
    //     // These methods primarily deal with top-level client management or are default SuperAdmin views.
    //     $this->middleware('auth:superadmin')->only([
    //         'indexClients', 'createClient', 'storeClient', 'showClient', 'editClient', 'updateClient', 'destroyClient',
    //         'indexBranches', 'createBranch', 'storeBranch', 'showBranch', 'editBranch', 'updateBranch', 'destroyBranch',
    //         'indexServiceTypes', 'createServiceType', 'storeServiceType', 'showServiceType', 'editServiceType', 'updateServiceType', 'destroyServiceType',
    //     ]);

    //     // Middleware for Admin routes (clinic-level management): Ensures only web users can access these methods.
    //     // The 'except' array specifies methods that are exclusively handled by the 'superadmin' guard.
    //     $this->middleware('auth:web')->except([
    //         'indexClients', 'createClient', 'storeClient', 'destroyClient', // These are SuperAdmin only at top-level
    //     ]);

    //     // Authorization logic within the constructor for both SuperAdmins and Clinic Admins/Staff.
    //     // This anonymous middleware runs after the authentication middleware.
    //     $this->middleware(function ($request, $next) {
    //         $webUser = Auth::guard('web')->user();
    //         $superAdminUser = Auth::guard('superadmin')->user();
    //         $routeName = $request->route()->getName();

    //         // SuperAdmin has full access to all methods in this controller
    //         // if they are authenticated via the 'superadmin' guard.
    //         if ($superAdminUser) {
    //             return $next($request);
    //         }

    //         // Clinic Admin/Staff permissions:
    //         // This block only executes if a web user is authenticated AND a SuperAdmin is NOT.
    //         if ($webUser) {
    //             // Authorization for admin.clients.* routes (Clinic Admin's own client profile)
    //             if (str_starts_with($routeName, 'admin.clients.')) {
    //                 $client = $request->route('client'); // Get the client model from the route parameters
    //                 // Ensure the web user is only accessing their own client's details
    //                 if ($client && $client->id !== $webUser->client_id) {
    //                     abort(403, 'Unauthorized: You can only manage your own client details.');
    //                 }
    //                 // Check general permission to view client details (even for their own)
    //                 if (!$webUser->hasPermission('view_clients')) {
    //                     abort(403, 'Unauthorized: You do not have permission to view client details.');
    //                 }
    //                 // No granular create/edit/delete checks here for clients, as web users can only view/edit their own.
    //                 // Edit/Update permissions are handled by the 'editClient' and 'updateClient' methods themselves.
    //             }
    //             // Authorization for admin.branches.* routes
    //             elseif (str_starts_with($routeName, 'admin.branches.')) {
    //                 $client = $request->route('client');
    //                 // Ensure the branch belongs to the authenticated web user's client
    //                 if ($client && $client->id !== $webUser->client_id) {
    //                     abort(403, 'Unauthorized: You can only manage branches for your own client.');
    //                 }
    //                 // Check general permission to view branches
    //                 if (!$webUser->hasPermission('view_branches')) {
    //                     abort(403, 'Unauthorized: You do not have permission to view branches.');
    //                 }
    //                 // Further granular checks for create/edit/delete branches
    //                 if (str_contains($routeName, 'create') || str_contains($routeName, 'store')) {
    //                     if (!$webUser->hasPermission('create_branches')) abort(403, 'Unauthorized: Create branches permission missing.');
    //                 } elseif (str_contains($routeName, 'edit') || str_contains($routeName, 'update')) {
    //                     if (!$webUser->hasPermission('edit_branches')) abort(403, 'Unauthorized: Edit branches permission missing.');
    //                 } elseif (str_contains($routeName, 'destroy')) {
    //                     if (!$webUser->hasPermission('delete_branches')) abort(403, 'Unauthorized: Delete branches permission missing.');
    //                 }
    //             }
    //             // Authorization for admin.service-types.* routes
    //             elseif (str_starts_with($routeName, 'admin.service-types.')) {
    //                 $client = $request->route('client');
    //                 // Ensure the service type belongs to the authenticated web user's client
    //                 if ($client && $client->id !== $webUser->client_id) {
    //                     abort(403, 'Unauthorized: You can only manage service types for your own client.');
    //                 }
    //                 // Check general permission to view service types
    //                 if (!$webUser->hasPermission('view_service_types')) {
    //                     abort(403, 'Unauthorized: You do not have permission to view service types.');
    //                 }
    //                 // Further granular checks for create/edit/delete service types
    //                 if (str_contains($routeName, 'create') || str_contains($routeName, 'store')) {
    //                     if (!$webUser->hasPermission('create_service_types')) abort(403, 'Unauthorized: Create service types permission missing.');
    //                 } elseif (str_contains($routeName, 'edit') || str_contains($routeName, 'update')) {
    //                     if (!$webUser->hasPermission('edit_service_types')) abort(403, 'Unauthorized: Edit service types permission missing.');
    //                 } elseif (str_contains($routeName, 'destroy')) {
    //                     if (!$webUser->hasPermission('delete_service_types')) abort(403, 'Unauthorized: Delete service types permission missing.');
    //                 }
    //             }
    //             // If a web user is authenticated and passes the checks for their specific route, allow access.
    //             return $next($request);
    //         }

    //         // If neither SuperAdmin nor Web user is authenticated, or if an unhandled route is hit.
    //         abort(403, 'Unauthorized access.');
    //     });
    // }

    public function __construct()
    {
        // Middleware for checking if either 'web' or 'superadmin' user is authenticated
        $this->middleware(function ($request, $next) {
            $webUser = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();

            // Allow full access if the superadmin is logged in
            if ($superAdminUser) {
                return $next($request);
            }

            // Admin user (web guard) requires specific permission to view the inventory
            if ($webUser) {
                // Check for 'view_medicines' permission on admin user
                if (!$webUser->hasPermission('view_medicines')) {
                    abort(403, 'Unauthorized: You do not have permission to view inventory.');
                }
                return $next($request);
            }

            // If neither superadmin nor admin (web) is authenticated
            abort(403, 'Unauthorized access.');
        });
    }

    /**
     * Helper to determine the correct view prefix based on the authenticated guard.
     *
     * @return string
     */
    protected function getViewPrefix(): string
    {
        return Auth::guard('superadmin')->check() ? 'superadmin-users.clients.' : 'admin-users.clients.';
    }

    /**
     * Display a listing of clients (SuperAdmin only).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexClients(Request $request)
    {
        // Explicit check for SuperAdmin, though middleware should handle this.
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can view all clients.');
        }

        $query = Client::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%') // Assuming 'email' is the contact email
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $clients = $query->latest()->paginate(10);

        // This view is exclusively for SuperAdmins, so no need for dynamic prefix here.
        return view('superadmin-users.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client (SuperAdmin only).
     *
     * @return \Illuminate\View\View
     */
    public function createClient()
    {
        // Explicit check for SuperAdmin, though middleware should handle this.
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can create clients.');
        }
        // This view is exclusively for SuperAdmins.
        return view('superadmin-users.clients.create');
    }

    /**
     * Store a newly created client in storage (SuperAdmin only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeClient(Request $request)
    {
        // Explicit check for SuperAdmin, though middleware should handle this.
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can store clients.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'required|email|unique:clients,contact_email', // Using 'email' as the contact email field
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean', // Assuming 'is_active' is a boolean field for status
        ]);

        $client = Client::create($request->all());

        // Redirect to SuperAdmin's client show page
        return redirect()->route('superadmin.clients.show', $client->id)
                         ->with('success', 'Client created successfully!');
    }

    /**
     * Display the specified client.
     * Accessible by SuperAdmin (any client) or Clinic Admin (their own client).
     *
     * @param  \App\Models\Admin\Client  $client
     * @return \Illuminate\View\View
     */
    public function showClient(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check (redundant if constructor middleware is perfect, but good for safety)
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view your own client details.');
        }

        // Paginate branches and service types for the client
        $branches = $client->branches()->latest()->paginate(5, ['*'], 'branches_page');
        $serviceTypes = $client->ServiceTypes()->latest()->paginate(5, ['*'], 'service_types_page');

        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'show', compact('client', 'branches', 'serviceTypes'));
    }

    /**
     * Show the form for editing the specified client.
     * Accessible by SuperAdmin (any client) or Clinic Admin (their own client).
     *
     * @param  \App\Models\Admin\Client  $client
     * @return \Illuminate\View\View
     */
    public function editClient(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only edit your own client details.');
        }

        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     * Accessible by SuperAdmin (any client) or Clinic Admin (their own client).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateClient(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only update your own client details.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($client->id)], // Using 'email'
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean', // Assuming 'is_active' is a boolean field for status
        ];

        $request->validate($rules);

        $client->update($request->all());

        // Determine redirect route based on authenticated user
        $redirectRouteName = $superAdminUser ? 'superadmin.clients.show' : 'admin.clients.show';
        return redirect()->route($redirectRouteName, $client->id)
                         ->with('success', 'Client updated successfully!');
    }

    /**
     * Remove the specified client from storage (SuperAdmin only).
     *
     * @param  \App\Models\Admin\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyClient(Client $client)
    {
        // Explicit check for SuperAdmin, though middleware should handle this.
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can delete clients.');
        }

        // Prevent deletion if client has associated data
        if ($client->branches()->exists() || $client->serviceTypes()->exists() || $client->users()->exists() || $client->medicines()->exists() || $client->revenueEntries()->exists() || $client->sales()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete client: It has associated branches, service types, users, medicines, revenue, or sales. Delete these first.']);
        }

        $client->delete();

        // Always redirect to SuperAdmin's client index
        return redirect()->route('superadmin.clients.index')
                         ->with('success', 'Client deleted successfully!');
    }

    // --- Branch Management ---

    /**
     * Display a listing of branches for a specific client.
     *
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\View\View
     */
    public function indexBranches(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view branches for your own client.');
        }

        $branches = $client->branches()->latest()->paginate(10);
        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'branches.index', compact('client', 'branches'));
    }

    /**
     * Show the form for creating a new branch for a client.
     *
     * @param Client $client
     * @return \Illuminate\View\View
     */
    public function createBranch(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || !$webUser->hasPermission('create_branches'))) {
            abort(403, 'Unauthorized: You do not have permission to create branches for this client.');
        }
        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'branches.create', compact('client'));
    }

    /**
     * Store a newly created branch in storage.
     *
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBranch(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || !$webUser->hasPermission('create_branches'))) {
            abort(403, 'Unauthorized: You do not have permission to store branches for this client.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean', // Assuming 'is_active' for status
        ]);

        $branch = $client->branches()->create($request->all());

        // Determine redirect route based on authenticated user
        $redirectRouteName = $superAdminUser ? 'superadmin.branches.show' : 'admin.branches.show';
        return redirect()->route($redirectRouteName, ['client' => $client->id, 'branch' => $branch->id])
                         ->with('success', 'Branch created successfully!');
    }

    /**
     * Display the specified branch.
     *
     * @param Client $client
     * @param Branch $branch
     * @return \Illuminate\View\View
     */
    public function showBranch(Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check: Ensure branch belongs to the client, and client belongs to the user (if web user)
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $branch->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view branches for your own client.');
        }

        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'branches.show', compact('client', 'branch'));
    }

    /**
     * Show the form for editing the specified branch.
     *
     * @param Client $client
     * @param Branch $branch
     * @return \Illuminate\View\View
     */
    public function editBranch(Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $branch->client_id !== $client->id || !$webUser->hasPermission('edit_branches'))) {
            abort(403, 'Unauthorized: You do not have permission to edit branches for this client.');
        }
        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'branches.edit', compact('client', 'branch'));
    }

    /**
     * Update the specified branch in storage.
     *
     * @param Request $request
     * @param Client $client
     * @param Branch $branch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBranch(Request $request, Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $branch->client_id !== $client->id || !$webUser->hasPermission('edit_branches'))) {
            abort(403, 'Unauthorized: You do not have permission to update branches for this client.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean', // Assuming 'is_active' for status
        ]);

        $branch->update($request->all());

        // Determine redirect route based on authenticated user
        $redirectRouteName = $superAdminUser ? 'superadmin.branches.show' : 'admin.branches.show';
        return redirect()->route($redirectRouteName, ['client' => $client->id, 'branch' => $branch->id])
                         ->with('success', 'Branch updated successfully!');
    }

    /**
     * Remove the specified branch from storage.
     *
     * @param Client $client
     * @param Branch $branch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBranch(Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $branch->client_id !== $client->id || !$webUser->hasPermission('delete_branches'))) {
            abort(403, 'Unauthorized: You do not have permission to delete branches for this client.');
        }

        $branch->delete();

        // Determine redirect route based on authenticated user
        $redirectRouteName = $superAdminUser ? 'superadmin.branches.index' : 'admin.branches.index';
        return redirect()->route($redirectRouteName, $client->id)
                         ->with('success', 'Branch deleted successfully!');
    }

    // --- Service Type Management ---

    /**
     * Display a listing of service types for a specific client.
     *
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\View\View
     */
    public function indexServiceTypes(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view service types for your own client.');
        }

        $serviceTypes = $client->clientServiceTypes()->latest()->paginate(10);
        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'service-types.index', compact('client', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new service type for a client.
     *
     * @param Client $client
     * @return \Illuminate\View\View
     */
    public function createServiceType(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || !$webUser->hasPermission('create_service_types'))) {
            abort(403, 'Unauthorized: You do not have permission to create service types for this client.');
        }
        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'service-types.create', compact('client'));
    }

    /**
     * Store a newly created service type in storage.
     *
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeServiceType(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || !$webUser->hasPermission('create_service_types'))) {
            abort(403, 'Unauthorized: You do not have permission to store service types for this client.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // UPDATED: Use 'client_service_types' as the table name
                Rule::unique('client_service_types')->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'default_price' => 'required|numeric|min:0', // Assuming 'price' field
            'is_active' => 'boolean', // Assuming 'is_active' for status
        ]);

        $serviceType = $client->ServiceTypes()->create($request->all()); // Assuming clientServiceTypes relationship

        // Determine redirect route based on authenticated user
        // Ensure these routes exist and are correctly named in your web.php
        $redirectRouteName = $superAdminUser ? 'superadmin.service-types.show' : 'service-types.show'; // Adjusted for common naming
        return redirect()->route($redirectRouteName, ['client' => $client->id, 'serviceType' => $serviceType->id])
                         ->with('success', 'Service type created successfully!');
    }
    /**
     * Display the specified service type.
     *
     * @param Client $client
     * @param ServiceType $serviceType
     * @return \Illuminate\View\View
     */
    public function showServiceType(Client $client, ClientServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check: Ensure service type belongs to the client, and client belongs to the user (if web user)
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $serviceType->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view service types for your own client.');
        }

        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'service-types.show', compact('client', 'serviceType'));
    }

    /**
     * Show the form for editing the specified service type.
     *
     * @param Client $client
     * @param ServiceType $serviceType
     * @return \Illuminate\View\View
     */
    public function editServiceType(Client $client, ClientServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $serviceType->client_id !== $client->id || !$webUser->hasPermission('edit_service_types'))) {
            abort(403, 'Unauthorized: You do not have permission to edit service types for this client.');
        }
        // Use dynamic view prefix
        return view($this->getViewPrefix() . 'service-types.edit', compact('client', 'serviceType'));
    }

    /**
     * Update the specified service type in storage.
     *
     * @param Request $request
     * @param Client $client
     * @param ServiceType $serviceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateServiceType(Request $request, Client $client, ServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $serviceType->client_id !== $client->id || !$webUser->hasPermission('edit_service_types'))) {
            abort(403, 'Unauthorized: You do not have permission to update service types for this client.');
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                // Unique rule scoped to the client_id, ignoring the current service type
                Rule::unique('service_types')->ignore($serviceType->id)->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'default_price' => 'required|numeric|min:0', // Assuming 'price' field
            'is_active' => 'boolean', // Assuming 'is_active' for status
        ];

        $request->validate($rules);

        $serviceType->update($request->all());

        // Determine redirect route based on authenticated user
        $redirectRouteName = $superAdminUser ? 'superadmin.service-types.show' : 'admin.service-types.show';
        return redirect()->route($redirectRouteName, ['client' => $client->id, 'serviceType' => $serviceType->id])
                         ->with('success', 'Service type updated successfully!');
    }

    /**
     * Remove the specified service type from storage.
     *
     * @param Client $client
     * @param ServiceType $serviceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyServiceType(Client $client, ServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id || $serviceType->client_id !== $client->id || !$webUser->hasPermission('delete_service_types'))) {
            abort(403, 'Unauthorized: You do not have permission to delete service types for this client.');
        }

        $serviceType->delete();

        // Determine redirect route based on authenticated user
        $redirectRouteName = $superAdminUser ? 'superadmin.service-types.index' : 'admin.service-types.index';
        return redirect()->route($redirectRouteName, $client->id)
                         ->with('success', 'Service type deleted successfully!');
    }
}
