<?php

namespace App\Http\Controllers\Web\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Client;
use App\Models\Admin\Branch;
use App\Models\Admin\ClientServiceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\SuperAdmin\SuperAdmin;

class ClientManagementController extends Controller
{
    public function __construct()
    {
        // This middleware ensures only logged-in users can access these routes.
        // It allows access if authenticated by 'web' OR 'superadmin' guard.
        // $this->middleware(['auth:web', 'auth:superadmin']);
        $this->middleware('auth:web,superadmin');
        // Authorization logic within the constructor to handle guard-specific access
        $this->middleware(function ($request, $next) {
            
            $webUser = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();

            // If a SuperAdmin is logged in, they have full access to all client management
            if ($superAdminUser) {
                return $next($request);
            }

            // If a web user (Clinic Admin/Staff) is logged in, apply permission checks
            if ($webUser) {
                $routeName = $request->route()->getName();

                // Specific permissions for ClientManagementController's own client data
                if (in_array($routeName, ['clients.show', 'clients.edit', 'clients.update'])) {
                    // For viewing/editing their own client, they need view_clients/edit_clients permission
                    // and the client ID must match their own. This is handled in the individual methods.
                    if (!$webUser->hasPermission('view_clients') && !$webUser->hasPermission('edit_clients')) {
                        abort(403, 'Unauthorized: You do not have permission to view/edit client data.');
                    }
                } elseif (str_starts_with($routeName, 'clients.index') || str_starts_with($routeName, 'clients.create') || str_starts_with($routeName, 'clients.store') || str_starts_with($routeName, 'clients.destroy')) {
                    // These are typically SuperAdmin-only client-level actions
                    abort(403, 'Unauthorized: Only SuperAdmins can manage top-level client records.');
                }
            } else {
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }

    // --- Client Management (SuperAdmin Only for index, create, store, destroy) ---

    /**
     * Display a listing of clients.
     * Accessible by SuperAdmin only.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexClients(Request $request)
    {
        // Authorization: Only SuperAdmins can view all clients
        if (!Auth::guard('superadmin')->check()) { // Check SuperAdmin guard
            abort(403, 'Unauthorized: Only SuperAdmins can view all clients.');
        }

        $query = Client::query();

        // Optional search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('contact_email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $clients = $query->paginate(10);

        return view('admin-users.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     * Accessible by SuperAdmin.
     *
     * @return \Illuminate\View\View
     */
    public function createClient()
    {
        if (!Auth::guard('superadmin')->check()) { // Check SuperAdmin guard
            abort(403, 'Unauthorized: Only SuperAdmins can create new clients.');
        }
        return view('admin-users.clients.create');
    }

    /**
     * Store a newly created client in storage.
     * Accessible by SuperAdmin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeClient(Request $request)
    {
        if (!Auth::guard('superadmin')->check()) { // Check SuperAdmin guard
            abort(403, 'Unauthorized: Only SuperAdmins can create new clients.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => 'required|string|email|max:255|unique:clients,contact_email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $client = Client::create($request->all());

        return redirect()->route('clients.show', $client->id)
                         ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified client.
     * Accessible by SuperAdmin or the client's own Admin.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function showClient(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can view any client. Clinic Admin can only view their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view your own client data.');
        }
        // Load relationships for display
        $client->load('branches', 'serviceTypes');
        return view('admin-users.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     * Accessible by SuperAdmin or the client's own Admin.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function editClient(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can edit any client. Clinic Admin can only edit their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only edit your own client data.');
        }
        return view('admin-users.clients.edit', compact('client'));
    }

    /**
     * Update the specified client in storage.
     * Accessible by SuperAdmin or the client's own Admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateClient(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can update any client. Clinic Admin can only update their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only update your own client data.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_email' => ['required', 'string', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $client->update($request->all());

        return redirect()->route('clients.show', $client->id)
                         ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage (soft delete).
     * Accessible by SuperAdmin only.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyClient(Client $client)
    {
        if (!Auth::guard('superadmin')->check()) { // Check SuperAdmin guard
            abort(403, 'Unauthorized: Only SuperAdmins can delete clients.');
        }
        // Soft delete the client. Related branches, service types, users, etc., will be handled by cascade/set null on FKs.
        $client->delete();

        return redirect()->route('clients.index')
                         ->with('success', 'Client deleted successfully.');
    }

    // --- Branch Management (Admin/SuperAdmin) ---

    /**
     * Display a listing of branches for a specific client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function indexBranches(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can view any client's branches. Clinic Admin can only view their own client's branches.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view branches for your own client.');
        }
        $branches = $client->branches()->paginate(10);
        return view('admin-users.clients.branches.index', compact('client', 'branches'));
    }

    /**
     * Show the form for creating a new branch for a client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function createBranch(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can add branches for any client. Clinic Admin can only add branches for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only add branches for your own client.');
        }
        return view('admin-users.clients.branches.create', compact('client'));
    }

    /**
     * Store a newly created branch for a client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBranch(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can store branches for any client. Clinic Admin can only store branches for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only add branches for your own client.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches')->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                }),
            ],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $branch = $client->branches()->create($request->all());

        return redirect()->route('branches.show', [$client->id, $branch->id])
                         ->with('success', 'Branch created successfully.');
    }

    /**
     * Display the specified branch.
     *
     * @param  \App\Models\Client  $client
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\View\View
     */
    public function showBranch(Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can view any branch. Clinic Admin can only view branches for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view branches for your own client.');
        }
        // Ensure the branch belongs to the client
        if ($branch->client_id !== $client->id) {
            abort(404); // Or redirect with error
        }
        return view('admin-users.clients.branches.show', compact('client', 'branch'));
    }

    /**
     * Show the form for editing the specified branch.
     *
     * @param  \App\Models\Client  $client
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\View\View
     */
    public function editBranch(Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can edit any branch. Clinic Admin can only edit branches for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only edit branches for your own client.');
        }
        if ($branch->client_id !== $client->id) {
            abort(404);
        }
        return view('admin-users.clients.branches.edit', compact('client', 'branch'));
    }

    /**
     * Update the specified branch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBranch(Request $request, Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can update any branch. Clinic Admin can only update branches for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only update branches for your own client.');
        }
        if ($branch->client_id !== $client->id) {
            abort(404);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('branches')->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                })->ignore($branch->id),
            ],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.show', [$client->id, $branch->id])
                         ->with('success', 'Branch updated successfully.');
    }

    /**
     * Remove the specified branch from storage (soft delete).
     *
     * @param  \App\Models\Client  $client
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBranch(Client $client, Branch $branch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can delete any branch. Clinic Admin can only delete branches for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only delete branches for your own client.');
        }
        if ($branch->client_id !== $client->id) {
            abort(404);
        }
        $branch->delete(); // Soft delete

        return redirect()->route('clients.show', $client->id)
                         ->with('success', 'Branch deleted successfully.');
    }

    // --- Client Service Type Management (Admin/SuperAdmin) ---

    /**
     * Display a listing of service types for a specific client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function indexServiceTypes(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can view any client's service types. Clinic Admin can only view their own client's service types.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view service types for your own client.');
        }
        $serviceTypes = $client->serviceTypes()->paginate(10);
        return view('admin-users.clients.service-types.index', compact('client', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new service type for a client.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\View\View
     */
    public function createServiceType(Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can add service types for any client. Clinic Admin can only add service types for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only add service types for your own client.');
        }
        return view('admin-users.clients.service-types.create', compact('client'));
    }

    /**
     * Store a newly created service type for a client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeServiceType(Request $request, Client $client)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can store service types for any client. Clinic Admin can only store service types for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only add service types for your own client.');
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('client_service_types')->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                }),
            ],
            'description' => 'nullable|string',
            'default_price' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $serviceType = $client->serviceTypes()->create($request->all());

        return redirect()->route('service-types.show', [$client->id, $serviceType->id])
                         ->with('success', 'Service type created successfully.');
    }

    /**
     * Display the specified service type.
     *
     * @param  \App\Models\Client  $client
     * @param  \App\Models\ClientServiceType  $serviceType
     * @return \Illuminate\View\View
     */
    public function showServiceType(Client $client, ClientServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can view any service type. Clinic Admin can only view service types for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only view service types for your own client.');
        }
        if ($serviceType->client_id !== $client->id) {
            abort(404);
        }
        return view('admin-users.clients.service-types.show', compact('client', 'serviceType'));
    }

    /**
     * Show the form for editing the specified service type.
     *
     * @param  \App\Models\Client  $client
     * @param  \App\Models\ClientServiceType  $serviceType
     * @return \Illuminate\View\View
     */
    public function editServiceType(Client $client, ClientServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can edit any service type. Clinic Admin can only edit service types for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only edit service types for your own client.');
        }
        if ($serviceType->client_id !== $client->id) {
            abort(404);
        }
        return view('admin-users.clients.service-types.edit', compact('client', 'serviceType'));
    }

    /**
     * Update the specified service type.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @param  \App\Models\ClientServiceType  $serviceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateServiceType(Request $request, Client $client, ClientServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can update any service type. Clinic Admin can only update service types for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only update service types for your own client.');
        }
        if ($serviceType->client_id !== $client->id) {
            abort(404);
        }

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('client_service_types')->where(function ($query) use ($client) {
                    return $query->where('client_id', $client->id);
                })->ignore($serviceType->id),
            ],
            'description' => 'nullable|string',
            'default_price' => 'nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);

        $serviceType->update($request->all());

        return redirect()->route('service-types.show', [$client->id, $serviceType->id])
                         ->with('success', 'Service type updated successfully.');
    }

    /**
     * Remove the specified service type from storage (soft delete).
     *
     * @param  \App\Models\Client  $client
     * @param  \App\Models\ClientServiceType  $serviceType
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyServiceType(Client $client, ClientServiceType $serviceType)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // SuperAdmin can delete any service type. Clinic Admin can only delete service types for their own client.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $client->id)) {
            abort(403, 'Unauthorized: You can only delete service types for your own client.');
        }
        if ($serviceType->client_id !== $client->id) {
            abort(404);
        }
        $serviceType->delete(); // Soft delete

        return redirect()->route('clients.show', $client->id)
                         ->with('success', 'Service type deleted successfully.');
    }
}
