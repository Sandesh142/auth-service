<?php

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin\Client; // Corrected namespace if it was Admin\Client
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\SuperAdmin\SuperAdmin; // To check if SuperAdmin is logged in

class UserManagementController extends Controller
{
    public function __construct()
    {
        // This middleware ensures only logged-in users can access these routes.
        // It allows access if authenticated by 'web' OR 'superadmin' guard.
        $this->middleware(['auth:web', 'auth:superadmin']);

        // Authorization logic within the constructor to handle guard-specific access
        $this->middleware(function ($request, $next) {
            $webUser = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();

            // If a SuperAdmin is logged in, they have full access to user management
            if ($superAdminUser) {
                return $next($request);
            }

            // If a web user (Clinic Admin/Staff) is logged in, apply permission checks
            if ($webUser) {
                // Clinic Admins need 'view_users' permission to access this module
                if (!$webUser->hasPermission('view_users')) {
                    abort(403, 'Unauthorized: You do not have permission to view users.');
                }
                // Further granular permissions (create, edit, delete) are checked in respective methods.
            } else {
                // If neither web nor superadmin user is authenticated, this middleware should not be reached
                // as 'auth:web,superadmin' should have redirected. But as a fallback:
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of users.
     * SuperAdmin sees all users. Admin sees users within their client.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = User::with('client', 'roles');

        if ($superAdminUser) {
            // SuperAdmin can filter by client
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            }
            $clients = Client::all(); // For filter dropdown
        } elseif ($webUser) {
            // Admin/Staff can only see users belonging to their client
            $query->where('client_id', $webUser->client_id);
            $clients = collect(); // No client filter for non-superadmins
        } else {
            // Should not be reached due to middleware, but as a fallback
            abort(403, 'Unauthorized: No authenticated user found.');
        }

        // Exclude the currently logged-in user from the list for security/simplicity
        // Apply this after determining the active user type
        if ($webUser) {
            $query->where('id', '!=', $webUser->id);
        } elseif ($superAdminUser) {
            // If SuperAdmin, they can see all users, but might want to exclude themselves
            // if they are also represented in the 'users' table (which they are in your setup)
            $query->where('id', '!=', $superAdminUser->id);
        }


        // Filter by system role (e.g., 'admin', 'staff')
        if ($request->has('system_role') && $request->system_role != '') {
            $query->where('role', $request->system_role);
        }

        // Filter by clinic-defined role
        if ($request->has('clinic_role_id') && $request->clinic_role_id != '') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->clinic_role_id);
            });
        }

        $usersToManage = $query->paginate(10);

        // Get roles for filter dropdown (clinic-specific for admin, all for superadmin)
        $clinicRoles = collect();
        if ($superAdminUser) {
            $clinicRoles = Role::all();
        } elseif ($webUser && $webUser->client_id) {
            $clinicRoles = Role::where('client_id', $webUser->client_id)->get();
        }

        return view('admin-users.users.index', compact('usersToManage', 'clients', 'clinicRoles'));
    }

    /**
     * Show the form for creating a new user.
     * SuperAdmin can create any user. Admin can create staff for their client.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_users'))) {
            abort(403, 'Unauthorized: You do not have permission to create users.');
        }

        $clients = collect(); // Default empty
        $roles = collect(); // Default empty

        if ($superAdminUser) {
            $clients = Client::all();
            // SuperAdmin can assign 'admin' or 'staff' system roles
            // They can also assign any clinic-defined role
            $roles = Role::all();
        } elseif ($webUser) { // Clinic Admin/Staff
            // Admins can only create 'staff' users for their own client
            // They can assign clinic-defined roles for their client
            if ($webUser->hasSystemRole('admin')) {
                $roles = Role::where('client_id', $webUser->client_id)->get();
            } else {
                abort(403, 'Unauthorized: Only Admins and SuperAdmins can create users.');
            }
        }

        return view('admin-users.users.create', compact('clients', 'roles'));
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

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_users'))) {
            abort(403, 'Unauthorized: You do not have permission to create users.');
        }

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id', // For clinic-defined roles
            'first_name' => 'nullable|string|max:255', // Added these for consistency with fillable
            'last_name' => 'nullable|string|max:255',
        ];

        if ($superAdminUser) {
            $rules['client_id'] = 'nullable|exists:clients,id';
            $rules['system_role'] = 'required|in:superadmin,admin,staff';
        } elseif ($webUser) { // Clinic Admin
            $rules['client_id'] = ['required', 'exists:clients,id', Rule::in([$webUser->client_id])]; // Must be their client_id
            $rules['system_role'] = ['required', Rule::in(['staff'])]; // Can only create 'staff'
        } else {
            abort(403, 'Unauthorized: No authenticated user to create users.');
        }

        $request->validate($rules);

        // Authorization check for role assignment
        if ($request->has('role_id') && $request->role_id) {
            $selectedRole = Role::find($request->role_id);
            if (!$selectedRole) {
                return back()->withErrors(['role_id' => 'Selected role does not exist.'])->withInput();
            }
            // SuperAdmin can assign any role
            // Clinic Admin can only assign roles belonging to their client
            if (!$superAdminUser && $selectedRole->client_id !== $webUser->client_id) {
                abort(403, 'Unauthorized: You can only assign roles defined for your client.');
            }
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'client_id' => $request->client_id,
            'role' => $request->system_role, // Store system-level role
            'first_name' => $request->first_name, // Added
            'last_name' => $request->last_name,   // Added
            'status' => 'active', // Default status
            'is_two_factor_enabled' => false, // Default
        ]);

        // Attach clinic-defined role if provided
        if ($request->has('role_id') && $request->role_id) {
            $newUser->roles()->attach($request->role_id);
        }

        return redirect()->route('users.show', $newUser->id)
                         ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     * SuperAdmin can view any user. Admin can view users within their client.
     *
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\View\View
     */
    public function show(User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization: SuperAdmin can view any user.
        // Admin/Staff can only view users belonging to their client.
        if ($superAdminUser) {
            // OK, SuperAdmin has full access
        } elseif ($webUser && $webUser->client_id === $userToManage->client_id) {
            // OK, Clinic Admin/Staff viewing user within their client
            if (!$webUser->hasPermission('view_users')) {
                abort(403, 'Unauthorized: You do not have permission to view users.');
            }
        } else {
            abort(403, 'Unauthorized: You can only view users within your client or you lack permission.');
        }

        $userToManage->load('client', 'roles'); // Load relationships for display
        return view('admin-users.users.show', compact('userToManage'));
    }

    /**
     * Show the form for editing the specified user.
     * SuperAdmin can edit any user. Admin can edit staff for their client.
     *
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\View\View
     */
    public function edit(User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization: SuperAdmin can edit any user.
        // Admin/Staff can only edit users belonging to their client.
        if ($superAdminUser) {
            // OK, SuperAdmin has full access
        } elseif ($webUser && $webUser->client_id === $userToManage->client_id) {
            if (!$webUser->hasPermission('edit_users')) {
                abort(403, 'Unauthorized: You do not have permission to edit users.');
            }
        } else {
            abort(403, 'Unauthorized: You can only edit users within your client or you lack permission.');
        }

        // Prevent editing SuperAdmin accounts by non-SuperAdmins
        // This check is crucial for security.
        if (!$superAdminUser && $userToManage->role === 'superadmin') {
            abort(403, 'Unauthorized: You cannot edit a SuperAdmin account.');
        }

        $clients = collect();
        $roles = collect();

        if ($superAdminUser) {
            $clients = Client::all();
            $roles = Role::all();
        } elseif ($webUser) { // Clinic Admin
            if ($webUser->hasSystemRole('admin')) {
                $roles = Role::where('client_id', $webUser->client_id)->get();
            } else {
                abort(403, 'Unauthorized: Only Admins and SuperAdmins can edit users.');
            }
        }

        $userToManage->load('roles'); // Load current roles
        $currentRoleIds = $userToManage->roles->pluck('id')->toArray();

        return view('admin-users.users.edit', compact('userToManage', 'clients', 'roles', 'currentRoleIds'));
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

        // Authorization: SuperAdmin can update any user.
        // Admin/Staff can only update users belonging to their client.
        if ($superAdminUser) {
            // OK, SuperAdmin has full access
        } elseif ($webUser && $webUser->client_id === $userToManage->client_id) {
            if (!$webUser->hasPermission('edit_users')) {
                abort(403, 'Unauthorized: You do not have permission to update users.');
            }
        } else {
            abort(403, 'Unauthorized: You can only update users within your client or you lack permission.');
        }

        // Prevent editing SuperAdmin accounts by non-SuperAdmins
        if (!$superAdminUser && $userToManage->role === 'superadmin') {
            abort(403, 'Unauthorized: You cannot update a SuperAdmin account.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($userToManage->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'nullable|exists:roles,id', // For clinic-defined roles
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
        ];

        if ($superAdminUser) {
            $rules['client_id'] = 'nullable|exists:clients,id';
            $rules['system_role'] = 'required|in:superadmin,admin,staff';
        } elseif ($webUser) { // Clinic Admin
            $rules['client_id'] = ['required', 'exists:clients,id', Rule::in([$webUser->client_id])]; // Must be their client_id
            $rules['system_role'] = ['required', Rule::in(['staff'])]; // Can only update to 'staff'
        } else {
            abort(403, 'Unauthorized: No authenticated user to update users.');
        }

        $request->validate($rules);

        $data = $request->only(['name', 'email', 'client_id', 'first_name', 'last_name']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $data['role'] = $request->system_role; // Update system-level role

        $userToManage->update($data);

        // Sync clinic-defined roles
        if ($request->has('role_id') && $request->role_id) {
            $selectedRole = Role::find($request->role_id);
            if (!$selectedRole) {
                return back()->withErrors(['role_id' => 'Selected role does not exist.'])->withInput();
            }
            // SuperAdmin can assign any role
            // Clinic Admin can only assign roles belonging to their client
            if (!$superAdminUser && $selectedRole->client_id !== $webUser->client_id) {
                abort(403, 'Unauthorized: You can only assign roles defined for your client.');
            }
            $userToManage->roles()->sync([$request->role_id]);
        } else {
            $userToManage->roles()->detach(); // Remove all roles if none selected
        }

        return redirect()->route('users.show', $userToManage->id)
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage (soft delete).
     * SuperAdmin can delete any user. Admin can delete staff for their client.
     *
     * @param  \App\Models\User  $userToManage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $userToManage)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Prevent self-deletion
        if (($webUser && $webUser->id === $userToManage->id) || ($superAdminUser && $superAdminUser->id === $userToManage->id)) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Authorization: SuperAdmin can delete any user.
        // Admin/Staff can only delete users belonging to their client.
        if ($superAdminUser) {
            // OK, SuperAdmin has full access
        } elseif ($webUser && $webUser->client_id === $userToManage->client_id) {
            if (!$webUser->hasPermission('delete_users')) {
                abort(403, 'Unauthorized: You do not have permission to delete users.');
            }
        } else {
            abort(403, 'Unauthorized: You can only delete users within your client or you lack permission.');
        }

        // Prevent non-SuperAdmins from deleting SuperAdmin accounts
        if (!$superAdminUser && $userToManage->role === 'superadmin') {
            abort(403, 'Unauthorized: You cannot delete a SuperAdmin account.');
        }

        // Prevent non-SuperAdmins from deleting Admin accounts (of other clients)
        // This check is important for Clinic Admins trying to delete other Admins
        if ($webUser && $webUser->hasSystemRole('admin') && $userToManage->hasSystemRole('admin') && $webUser->client_id !== $userToManage->client_id) {
            abort(403, 'Unauthorized: You cannot delete another Admin account from a different client.');
        }
        // Also prevent a Clinic Admin from deleting an Admin from their own client if you wish
        // Currently, a Clinic Admin can delete a Staff, but not another Admin.
        if ($webUser && $webUser->hasSystemRole('admin') && $userToManage->hasSystemRole('admin') && $webUser->client_id === $userToManage->client_id) {
            abort(403, 'Unauthorized: You cannot delete another Admin account from your own client.');
        }


        $userToManage->delete(); // Soft delete

        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully.');
    }
}
