<?php

namespace App\Http\Controllers\web\Superadmin\Roles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role; // Assuming this is your custom Role model, not Spatie's directly if you extended it
use App\Models\Admin\Permission; // Assuming this is your custom Permission model
use App\Models\User; // Your Admin/Staff user model
use App\Models\Admin\Client; // Your Client model
use App\Models\SuperAdmin\SuperAdmin; // Your SuperAdmin user model
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
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
                // Handle 'admin.roles.*' routes (Clinic-level role management)
                if (str_starts_with($routeName, 'admin.roles.')) {
                    // Check base permission to view roles
                    if (!$webUser->hasPermission('view_roles')) {
                        abort(403, 'Unauthorized: You do not have permission to view clinic roles.');
                    }

                    // For specific role actions (show, edit, update, destroy), ensure the role belongs to the user's client.
                    if (in_array($routeName, ['admin.roles.show', 'admin.roles.edit', 'admin.roles.update', 'admin.roles.destroy'])) {
                        $role = $request->route('role');
                        // If the role exists and its client_id does not match the web user's client_id (or if it's a system role)
                        if ($role && $role->client_id !== $webUser->client_id) {
                            abort(403, 'Unauthorized: You can only manage roles for your own clinic.');
                        }
                    }

                    // Check specific action permissions for roles
                    if ((str_contains($routeName, 'create') || str_contains($routeName, 'store')) && !$webUser->hasPermission('create_roles')) {
                        abort(403, 'Unauthorized: You do not have permission to create clinic roles.');
                    }
                    if ((str_contains($routeName, 'edit') || str_contains($routeName, 'update')) && !$webUser->hasPermission('edit_roles')) {
                        abort(403, 'Unauthorized: You do not have permission to edit clinic roles.');
                    }
                    if (str_contains($routeName, 'destroy') && !$webUser->hasPermission('delete_roles')) {
                        abort(403, 'Unauthorized: You do not have permission to delete clinic roles.');
                    }
                }
                // Handle 'superadmin.roles.*' or 'permissions.*' routes if a web user somehow tries to access them.
                // These routes should ideally be protected by route-level 'auth:superadmin' middleware,
                // but this acts as a safety net for web users.
                elseif (str_starts_with($routeName, 'superadmin.roles.') || str_starts_with($routeName, 'superadmin.permissions.')) {
                    abort(403, 'Unauthorized: Clinic staff cannot access system-wide role or permission management.');
                }

                // If a web user is authenticated and passes the above checks, allow access.
                return $next($request);
            }

            // 3. If neither SuperAdmin nor Web user is authenticated (should be caught by route middleware first).
            abort(403, 'Unauthorized access: No authenticated user.');
        });
    }

    /**
     * Helper to determine the correct view prefix for roles.
     *
     * @return string
     */
    protected function getRolesViewPrefix(): string
    {
        return Auth::guard('superadmin')->check() ? 'superadmin-users.roles.' : 'admin-users.roles.';
    }

    /**
     * Helper to determine the correct view prefix for permissions.
     * Permissions management is always SuperAdmin only.
     *
     * @return string
     */
    protected function getPermissionsViewPrefix(): string
    {
        return 'superadmin-users.permissions.';
    }

    /**
     * Display a listing of roles.
     * SuperAdmin sees all roles; Clinic Admin/Staff see roles for their client.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexRoles(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();
        $query = Role::query();
        $clients = collect(); // Initialize as empty collection

        if ($superAdminUser) {
            // SuperAdmin can filter by client_id, including 'null' for system roles
            if ($request->has('client_id') && $request->client_id !== '') {
                if ($request->client_id === 'null') {
                    $query->whereNull('client_id');
                } else {
                    $query->where('client_id', $request->client_id);
                }
            }
            $clients = Client::all(); // SuperAdmin needs all clients for the filter dropdown
        } elseif ($webUser) {
            // Clinic Admin/Staff can only see roles for their own client
            $query->where('client_id', $webUser->client_id);
            $clients = Client::where('id', $webUser->client_id)->get(); // Only their client for dropdown
        }

        $roles = $query->with('permissions')->latest()->paginate(10);

        return view($this->getRolesViewPrefix() . 'index', compact('roles', 'clients'));
    }

    /**
     * Show the form for creating a new role.
     * SuperAdmin can create system roles or client-specific roles.
     * Clinic Admin/Staff can only create roles for their own client.
     *
     * @return \Illuminate\View\View
     */
    public function createRole()
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_roles'))) {
            abort(403, 'Unauthorized: You do not have permission to create roles.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all(); // SuperAdmin needs all clients for the dropdown
        } elseif ($webUser && $webUser->client_id) {
            $clients = Client::where('id', $webUser->client_id)->get(); // Clinic Admin only sees their client
        }

        $permissions = Permission::all(); // All available permissions for assignment

        return view($this->getRolesViewPrefix() . 'create', compact('clients', 'permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRole(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_roles'))) {
            abort(403, 'Unauthorized: You do not have permission to create roles.');
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive', // Assuming 'active'/'inactive' for role status
            'guard_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];

        $clientId = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'nullable|exists:clients,id';
            $clientId = $request->client_id; // SuperAdmin can specify client_id or leave null
        } elseif ($webUser && $webUser->client_id) {
            // Clinic Admin/Staff can only create roles for their own client
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientId = $webUser->client_id;
        } else {
            abort(403, 'Unauthorized: Cannot determine client for role creation.');
        }

        // Add unique rule for name, scoped by client_id (or null for system roles)
        $rules['name'][] = Rule::unique('roles')->where(function ($query) use ($clientId) {
            return $clientId ? $query->where('client_id', $clientId) : $query->whereNull('client_id');
        });

        $request->validate($rules);

        $role = Role::create([
            'client_id' => $clientId,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'guard_name' => $request->guard_name,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        // Redirect based on who created the role
        $redirectRouteName = $superAdminUser ? 'superadmin.roles.index' : 'admin.roles.index';
        return redirect()->route($redirectRouteName)->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     * SuperAdmin can view any role. Clinic Admin/Staff can view roles for their client.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function showRole(Role $role)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization check (redundant if constructor middleware is perfect, but good for safety)
        if (!$superAdminUser && ($role->client_id !== ($webUser->client_id ?? null))) {
            abort(403, 'Unauthorized: You can only view roles for your own client.');
        }

        $role->load('permissions'); // Eager load permissions for display
        return view($this->getRolesViewPrefix() . 'show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     * SuperAdmin can edit any role. Clinic Admin/Staff can edit roles for their client.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function editRole(Role $role)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('edit_roles'))) {
            abort(403, 'Unauthorized: You do not have permission to edit roles.');
        }

        // Ensure Clinic Admin/Staff can only edit roles for their own client
        if (!$superAdminUser && ($role->client_id !== ($webUser->client_id ?? null))) {
            abort(403, 'Unauthorized: You can only edit roles for your own client.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all(); // SuperAdmin needs all clients for the dropdown
        } elseif ($webUser && $webUser->client_id) {
            $clients = Client::where('id', $webUser->client_id)->get(); // Clinic Admin only sees their client
        }

        $permissions = Permission::all(); // All available permissions for assignment
        $currentPermissionIds = $role->permissions->pluck('id')->toArray(); // Get IDs of currently assigned permissions

        return view($this->getRolesViewPrefix() . 'edit', compact('role', 'clients', 'permissions', 'currentPermissionIds'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, Role $role)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('edit_roles'))) {
            abort(403, 'Unauthorized: You do not have permission to update roles.');
        }

        // Ensure Clinic Admin/Staff can only update roles for their own client
        if (!$superAdminUser && ($role->client_id !== ($webUser->client_id ?? null))) {
            abort(403, 'Unauthorized: You can only update roles for your own client.');
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive', // Assuming 'active'/'inactive' for role status
            'guard_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];

        $clientId = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'nullable|exists:clients,id';
            $clientId = $request->client_id; // SuperAdmin can specify client_id or leave null
        } elseif ($webUser && $webUser->client_id) {
            // Clinic Admin/Staff can only update roles for their own client, and cannot change client_id
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientId = $webUser->client_id;
            // Explicitly prevent changing client_id for existing roles by Clinic Admin
            if ($request->client_id !== (string)$role->client_id) { // Cast to string for strict comparison with request value
                abort(403, 'Unauthorized: You cannot change the client for this role.');
            }
        } else {
            abort(403, 'Unauthorized: Cannot determine client for role update.');
        }

        // Add unique rule for name, scoped by client_id (or null for system roles), ignoring the current role
        $rules['name'][] = Rule::unique('roles')->ignore($role->id)->where(function ($query) use ($clientId) {
            return $clientId ? $query->where('client_id', $clientId) : $query->whereNull('client_id');
        });

        $request->validate($rules);

        $role->update([
            'client_id' => $clientId,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'guard_name' => $request->guard_name,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        // Redirect based on who updated the role
        $redirectRouteName = $superAdminUser ? 'superadmin.roles.show' : 'admin.roles.show';
        return redirect()->route($redirectRouteName, $role->id)->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage (soft delete).
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyRole(Role $role)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Permissions are checked in the constructor middleware, but this is a fallback.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('delete_roles'))) {
            abort(403, 'Unauthorized: You do not have permission to delete roles.');
        }

        // Ensure Clinic Admin/Staff can only delete roles for their own client
        if (!$superAdminUser && ($role->client_id !== ($webUser->client_id ?? null))) {
            abort(403, 'Unauthorized: You can only delete roles for your own client.');
        }

        $role->delete();

        // Redirect based on who deleted the role
        $redirectRouteName = $superAdminUser ? 'superadmin.roles.index' : 'admin.roles.index';
        return redirect()->route($redirectRouteName)->with('success', 'Role deleted successfully.');
    }


    // --- System-wide Permission Management (SuperAdmin Only) ---

    /**
     * Display a listing of system-wide permissions.
     * Exclusively for SuperAdmins.
     *
     * @return \Illuminate\View\View
     */
    public function indexPermissions()
    {
        // This check is primarily handled by route middleware and constructor middleware,
        // but explicit check here ensures clarity.
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can manage system permissions.');
        }

        $permissions = Permission::latest()->paginate(10);
        return view($this->getPermissionsViewPrefix() . 'index', compact('permissions'));
    }

    /**
     * Show the form for creating a new system-wide permission.
     * Exclusively for SuperAdmins.
     *
     * @return \Illuminate\View\View
     */
    public function createPermission()
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can create system permissions.');
        }
        return view($this->getPermissionsViewPrefix() . 'create');
    }

    /**
     * Store a newly created system-wide permission in storage.
     * Exclusively for SuperAdmins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePermission(Request $request)
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can store system permissions.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'required|string|max:255',
        ]);

        Permission::create($request->all());

        // Redirect to SuperAdmin's permission index
        return redirect()->route('superadmin.permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified system-wide permission.
     * Exclusively for SuperAdmins.
     *
     * @param  \App\Models\Admin\Permission  $permission
     * @return \Illuminate\View\View
     */
    public function editPermission(Permission $permission)
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can edit system permissions.');
        }
        return view($this->getPermissionsViewPrefix() . 'edit', compact('permission'));
    }

    /**
     * Update the specified system-wide permission in storage.
     * Exclusively for SuperAdmins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can update system permissions.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string|max:255',
        ]);

        $permission->update($request->all());

        // Redirect to SuperAdmin's permission index
        return redirect()->route('superadmin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified system-wide permission from storage (soft delete).
     * Exclusively for SuperAdmins.
     *
     * @param  \App\Models\Admin\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPermission(Permission $permission)
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can delete system permissions.');
        }

        $permission->delete();

        // Redirect to SuperAdmin's permission index
        return redirect()->route('superadmin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
