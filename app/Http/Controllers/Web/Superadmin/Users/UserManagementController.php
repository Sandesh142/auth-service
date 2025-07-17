<?php

namespace App\Http\Controllers\web\Superadmin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin\Client;
use App\Models\Role; // Assuming Role model is in App\Models
use App\Models\SuperAdmin\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class UserManagementController extends Controller
{
    public function __construct()
    {
        // This middleware handles granular authorization (permission checks and client scoping).
        // The 'auth:guard' middleware (e.g., 'auth:superadmin', 'auth:web') should be applied
        // at the route group level in your web.php or superadmin.php routes file.
        $this->middleware(function ($request, $next) {
            $webUser = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();
            $routeName = $request->route()->getName();

            // 1. SuperAdmin has full access to all methods in this controller.
            if ($superAdminUser) {
                return $next($request);
            }

            // 2. Clinic Admin/Staff permissions:
            // This block only executes if a web user is authenticated AND a SuperAdmin is NOT.
            if ($webUser) {
                // Handle 'admin.users.*' routes (Clinic-level user management)
                if (str_starts_with($routeName, 'admin.users.')) {
                    // Check base permission to view users
                    if (!$webUser->hasPermission('view_users')) {
                        abort(403, 'Unauthorized: You do not have permission to view users.');
                    }

                    // For specific user actions (show, edit, update, destroy), ensure the user belongs to the web user's client.
                    if (in_array($routeName, ['admin.users.show', 'admin.users.edit', 'admin.users.update', 'admin.users.destroy'])) {
                        $userToManage = $request->route('user'); // Assuming route parameter is 'user'
                        if ($userToManage && $userToManage->client_id !== $webUser->client_id) {
                            abort(403, 'Unauthorized: You can only manage users from your own clinic.');
                        }
                    }

                    // Check specific action permissions for users
                    if ((str_contains($routeName, 'create') || str_contains($routeName, 'store')) && !$webUser->hasPermission('create_users')) {
                        abort(403, 'Unauthorized: You do not have permission to create users.');
                    }
                    if ((str_contains($routeName, 'edit') || str_contains($routeName, 'update')) && !$webUser->hasPermission('edit_users')) {
                        abort(403, 'Unauthorized: You do not have permission to edit users.');
                    }
                    if (str_contains($routeName, 'destroy') && !$webUser->hasPermission('delete_users')) {
                        abort(403, 'Unauthorized: You do not have permission to delete users.');
                    }
                }
                // Handle 'superadmin.users.*' routes if a web user somehow tries to access them.
                elseif (str_starts_with($routeName, 'superadmin.users.')) {
                    abort(403, 'Unauthorized: Clinic staff cannot access system-wide user management.');
                }

                // If a web user is authenticated and passes the above checks, allow access.
                return $next($request);
            }

            // 3. If neither SuperAdmin nor Web user is authenticated (should be caught by route middleware first).
            abort(403, 'Unauthorized access: No authenticated user.');
        });
    }

    /**
     * Helper to determine the correct view prefix.
     *
     * @return string
     */
    protected function getViewPrefix(): string
    {
        return Auth::guard('superadmin')->check() ? 'superadmin-users.users.' : 'admin-users.users.';
    }

    /**
     * Display a listing of users.
     * SuperAdmin sees all users. Admin/Staff see users within their client.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = User::query();
        $clients = collect(); // Initialize as empty collection
        $clinicRoles = collect(); // Initialize as empty collection

        if ($superAdminUser) {
            // SuperAdmin can filter by client_id (including null for system users)
            if ($request->has('client_id') && $request->client_id !== '') {
                if ($request->client_id === 'null') {
                    $query->whereNull('client_id');
                } else {
                    $query->where('client_id', $request->client_id);
                }
            }
            // SuperAdmin can filter by system role
            if ($request->has('system_role') && $request->system_role !== '') {
                $query->where('role', $request->system_role);
            }
            $clients = Client::all(); // SuperAdmin needs all clients for filter dropdown
            $clinicRoles = Role::whereNotNull('client_id')->get(); // All clinic roles for filter dropdown
        } elseif ($webUser) {
            // Clinic Admin/Staff can only see users for their own client
            $query->where('client_id', $webUser->client_id);
            // Clinic Admin/Staff can filter by system role (only 'staff' for them)
            if ($request->has('system_role') && $request->system_role !== '') {
                // Ensure they can only filter for 'staff'
                if ($request->system_role === 'staff') {
                    $query->where('role', 'staff');
                } else {
                    // If they try to filter for 'admin' or 'superadmin', return no results
                    $query->whereRaw('1 = 0'); // Ensures no results are returned
                }
            }
            $clients = Client::where('id', $webUser->client_id)->get(); // Only their client for dropdown
            $clinicRoles = Role::where('client_id', $webUser->client_id)->get(); // Only their clinic's roles for dropdown
        } else {
            abort(403, 'Unauthorized: No authenticated user found.');
        }

        // Add search by name or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by clinic_role_id (for both SuperAdmin and Clinic Admin)
        if ($request->has('clinic_role_id') && $request->clinic_role_id !== '') {
            // Now filtering by the direct 'role_id' column on the users table
            $query->where('role_id', $request->clinic_role_id);
        }

        // Eager load 'client' and 'clinicRole' (the single clinic role)
        $usersToManage = $query->with('client', 'clinicRole')->latest()->paginate(10);

        return view($this->getViewPrefix() . 'index', compact('usersToManage', 'clients', 'clinicRoles'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_users'))) {
            abort(403, 'Unauthorized: You do not have permission to create users.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all(); // SuperAdmin needs all clients for the dropdown
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get(); // Clinic Admin only sees their client
        }

        $clinicRoles = collect();
        if ($superAdminUser) {
            $clinicRoles = Role::whereNotNull('client_id')->get(); // SuperAdmin can assign any clinic role
        } elseif ($webUser && $webUser->client_id) {
            $clinicRoles = Role::where('client_id', $webUser->client_id)->get(); // Admin can only assign their clinic's roles
        }

        return view($this->getViewPrefix() . 'create', compact('clients', 'clinicRoles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_users'))) {
            abort(403, 'Unauthorized: You do not have permission to store users.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'is_active' => 'boolean',
        ];

        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'nullable|exists:clients,id'; // SuperAdmin can create system users (null client_id)
            $clientIdForValidation = $request->client_id;
            $rules['system_role'] = 'required|string|in:superadmin,admin,staff'; // SuperAdmin can create other superadmins, admins, staff
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
            $rules['system_role'] = ['required', 'string', Rule::in(['staff'])]; // Clinic Admin can only create staff
        } else {
            abort(403, 'Unauthorized: No authenticated user to store users.');
        }

        // Validate clinic_role_id based on client_id for a single role
        $rules['clinic_role_id'] = [
            'nullable', // Allow no clinic role to be selected
            Rule::exists('roles', 'id')->where(function ($query) use ($clientIdForValidation, $superAdminUser) {
                if ($superAdminUser) {
                    // SuperAdmin can assign any clinic role (where client_id is not null)
                    $query->whereNotNull('client_id');
                } else {
                    // Clinic Admin can only assign roles for their own client
                    $query->where('client_id', $clientIdForValidation);
                }
            }),
        ];

        $request->validate($rules);

        $userToCreate = User::create([
            'client_id' => $clientIdForValidation,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->system_role, // Use 'system_role' from request
            'status' => $request->boolean('is_active'),
            'role_id' => $request->clinic_role_id, // Assign the clinic_role_id directly to the role_id column
        ]);

        // Removed $userToCreate->roles()->attach() as we are now using a direct foreign key

        $redirectRouteName = $superAdminUser ? 'superadmin.users.show' : 'admin.users.show';
        return redirect()->route($redirectRouteName, $userToCreate->id)
                         ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\View\View
     */
    public function show(User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check (redundant if constructor middleware is perfect, but good for safety)
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $userToManage->client_id)) {
            abort(403, 'Unauthorized: You can only view users from your own client.');
        }

        // Eager load 'client' and 'clinicRole' (the single clinic role)
        $userToManage->load('client', 'clinicRole');
        return view($this->getViewPrefix() . 'show', compact('userToManage'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\View\View
     */
    public function edit(User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $userToManage->client_id || !$webUser->hasPermission('edit_users'))) {
            abort(403, 'Unauthorized: You do not have permission to edit users from this client.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        $clinicRoles = collect();
        if ($superAdminUser) {
            $clinicRoles = Role::whereNotNull('client_id')->get(); // SuperAdmin can assign any clinic role
        } elseif ($webUser && $webUser->client_id) {
            $clinicRoles = Role::where('client_id', $webUser->client_id)->get(); // Admin can only assign their clinic's roles
        }

        // Get the single clinic role ID for pre-selection
        $currentClinicRoleId = $userToManage->role_id; // Directly get the role_id from the user

        return view($this->getViewPrefix() . 'edit', compact('userToManage', 'clients', 'clinicRoles', 'currentClinicRoleId'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $userToManage->client_id || !$webUser->hasPermission('edit_users'))) {
            abort(403, 'Unauthorized: You do not have permission to update users from this client.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userToManage->id)],
            'password' => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'is_active' => 'boolean',
        ];

        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'nullable|exists:clients,id';
            $clientIdForValidation = $request->client_id;
            $rules['system_role'] = 'required|string|in:superadmin,admin,staff';
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
            $rules['system_role'] = ['required', 'string', Rule::in(['staff'])]; // Clinic Admin can only update staff
            // Prevent Clinic Admin from changing the client_id of a user
            if ($request->client_id !== (string)$userToManage->client_id) { // Cast to string for strict comparison
                abort(403, 'Unauthorized: You cannot change the client for this user.');
            }
        } else {
            abort(403, 'Unauthorized: No authenticated user to update users.');
        }

        // Validate clinic_role_id based on client_id for a single role
        $rules['clinic_role_id'] = [
            'nullable', // Allow no clinic role to be selected
            Rule::exists('roles', 'id')->where(function ($query) use ($clientIdForValidation, $superAdminUser) {
                if ($superAdminUser) {
                    // SuperAdmin can assign any clinic role (where client_id is not null)
                    $query->whereNotNull('client_id');
                } else {
                    // Clinic Admin can only assign roles for their own client
                    $query->where('client_id', $clientIdForValidation);
                }
            }),
        ];

        $request->validate($rules);

        $userData = $request->only(['name', 'email', 'is_active']);
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $userData['client_id'] = $clientIdForValidation; // Ensure client_id is set correctly
        $userData['role'] = $request->system_role; // Use 'system_role' from request
        $userData['role_id'] = $request->clinic_role_id; // Assign the clinic_role_id directly to the role_id column

        $userToManage->update($userData);

        // Removed $userToManage->roles()->sync() and detach() as we are now using a direct foreign key

        $redirectRouteName = $superAdminUser ? 'superadmin.users.show' : 'admin.users.show';
        return redirect()->route($redirectRouteName, $userToManage->id)
                         ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $userToManage->client_id || !$webUser->hasPermission('delete_users'))) {
            abort(403, 'Unauthorized: You do not have permission to delete users from this client.');
        }

        // Prevent a user from deleting themselves
        if (($webUser && $webUser->id === $userToManage->id) || ($superAdminUser && $superAdminUser->id === $userToManage->id)) {
            return back()->withErrors(['error' => 'You cannot delete your own user account.']);
        }

        // Prevent SuperAdmin from deleting another SuperAdmin (optional, but good practice)
        if ($superAdminUser && $userToManage->role === 'superadmin') {
            return back()->withErrors(['error' => 'SuperAdmins cannot delete other SuperAdmin accounts.']);
        }

        $userToManage->delete();

        $redirectRouteName = $superAdminUser ? 'superadmin.users.index' : 'admin.users.index';
        return redirect()->route($redirectRouteName)
                         ->with('success', 'User deleted successfully.');
    }
}
