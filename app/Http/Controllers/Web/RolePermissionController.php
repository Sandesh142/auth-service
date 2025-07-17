<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Admin\Permission;
use App\Models\User;
use App\Models\Admin\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        //  $this->middleware(['auth:web', 'auth:superadmin']); // Ensure user is logged in
        $this->middleware('auth.any:web,superadmin');

        $this->middleware(function ($request, $next) {
            // echo "dsfdsfdsf"; die;
            $user = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();

            if ($superAdminUser) {
                return $next($request);
            }

            // Clinic Admins/Staff need specific permissions
            // For clinic roles: view_roles, create_roles, edit_roles, delete_roles, assign_roles
            // For system permissions (indexPermissions, etc.): only SuperAdmin should access
            $routeName = $request->route()->getName();

            if (str_starts_with($routeName, 'permissions.')) {
                // Only SuperAdmin can manage system-wide permissions
                if (!$superAdminUser) {
                    abort(403, 'Unauthorized: Only SuperAdmins can manage system permissions.');
                }
            } elseif (str_starts_with($routeName, 'roles.')) {
                // Clinic Admins/Staff need specific role permissions
                if (!$user || !$user->hasPermission('view_roles')) {
                    abort(403, 'Unauthorized: You do not have permission to manage clinic roles.');
                }
            } else {
                // Fallback for any unhandled routes within this controller
                abort(403, 'Unauthorized access.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of clinic-defined roles.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexRoles(Request $request)
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();
        $query = Role::query();

        if ($superAdminUser) {
            // SuperAdmin can filter roles by client
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            } else {
                // By default, SuperAdmin sees all clinic-specific roles (where client_id is not null)
                // and system roles (where client_id is null).
                // If you want SuperAdmin to ONLY see clinic roles by default, add ->whereNotNull('client_id')
                // If you want SuperAdmin to ONLY see system roles by default, add ->whereNull('client_id')
            }
            $clients = Client::all(); // SuperAdmin can see all clients for filtering
        } else {
            // Clinic Admin/Staff can only see roles for their own client
            $query->where('client_id', $user->client_id);
            $clients = Client::where('id', $user->client_id)->get(); // Only their client
        }

        $roles = $query->with('permissions')->latest()->paginate(10);

        return view('admin-users.roles.index', compact('roles', 'clients'));
    }

    /**
     * Show the form for creating a new clinic-defined role.
     *
     * @return \Illuminate\View\View
     */
    public function createRole()
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && !$user->hasPermission('create_roles')) {
            abort(403, 'Unauthorized: You do not have permission to create roles.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($user->client_id) {
            $clients = Client::where('id', $user->client_id)->get();
        }

        $permissions = Permission::all(); // All available permissions

        return view('admin-users.roles.create', compact('clients', 'permissions'));
    }

    /**
     * Store a newly created clinic-defined role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRole(Request $request)
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && !$user->hasPermission('create_roles')) {
            abort(403, 'Unauthorized: You do not have permission to create roles.');
        }

        $request->validate([
            'client_id' => [
                'nullable', // Nullable for system roles (if SuperAdmin creates them)
                Rule::requiredIf(function () use ($superAdminUser, $request) {
                    // If SuperAdmin is creating a role and client_id is provided, it's required.
                    // If Clinic Admin is creating, client_id is implicitly theirs.
                    return !$superAdminUser && !Auth::user()->client_id;
                }),
                'exists:clients,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($request, $superAdminUser, $user) {
                    // Unique role name per client (or global if client_id is null)
                    if ($superAdminUser && $request->client_id) {
                        $query->where('client_id', $request->client_id);
                    } elseif ($user->client_id) {
                        $query->where('client_id', $user->client_id);
                    } else {
                        $query->whereNull('client_id'); // For system-wide roles
                    }
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive', // Assuming enum 'active', 'inactive'
            'guard_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $clientId = $request->client_id;
        if (!$superAdminUser && $user->client_id) {
            $clientId = $user->client_id; // Clinic Admin implicitly creates for their client
        }

        $role = Role::create([
            'client_id' => $clientId,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'guard_name' => $request->guard_name,
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified clinic-defined role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function showRole(Role $role)
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Authorization: SuperAdmin can view any role. Clinic Admin/Staff can only view roles for their client.
        if (!$superAdminUser && $role->client_id !== $user->client_id) {
            abort(403, 'Unauthorized: You can only view roles for your own client.');
        }

        $role->load('permissions'); // Eager load permissions
        return view('admin-users.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified clinic-defined role.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function editRole(Role $role)
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && !$user->hasPermission('edit_roles')) {
            abort(403, 'Unauthorized: You do not have permission to edit roles.');
        }

        // Authorization: SuperAdmin can edit any role. Clinic Admin/Staff can only edit roles for their client.
        if (!$superAdminUser && $role->client_id !== $user->client_id) {
            abort(403, 'Unauthorized: You can only edit roles for your own client.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($user->client_id) {
            $clients = Client::where('id', $user->client_id)->get();
        }

        $permissions = Permission::all();
        $currentPermissionIds = $role->permissions->pluck('id')->toArray();

        return view('admin-users.roles.edit', compact('role', 'clients', 'permissions', 'currentPermissionIds'));
    }

    /**
     * Update the specified clinic-defined role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRole(Request $request, Role $role)
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && !$user->hasPermission('edit_roles')) {
            abort(403, 'Unauthorized: You do not have permission to edit roles.');
        }

        // Authorization: SuperAdmin can update any role. Clinic Admin/Staff can only update roles for their client.
        if (!$superAdminUser && $role->client_id !== $user->client_id) {
            abort(403, 'Unauthorized: You can only update roles for your own client.');
        }

        $request->validate([
            'client_id' => [
                'nullable',
                Rule::requiredIf(function () use ($superAdminUser, $request) {
                    return !$superAdminUser && !Auth::user()->client_id;
                }),
                'exists:clients,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id)->where(function ($query) use ($request, $superAdminUser, $user) {
                    if ($superAdminUser && $request->client_id) {
                        $query->where('client_id', $request->client_id);
                    } elseif ($user->client_id) {
                        $query->where('client_id', $user->client_id);
                    } else {
                        $query->whereNull('client_id');
                    }
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'guard_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $clientId = $request->client_id;
        if (!$superAdminUser && $user->client_id) {
            $clientId = $user->client_id;
        }

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
            $role->permissions()->detach(); // Detach all if no permissions are selected
        }

        return redirect()->route('roles.show', $role->id)->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified clinic-defined role from storage (soft delete).
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyRole(Role $role)
    {
        $user = Auth::user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && !$user->hasPermission('delete_roles')) {
            abort(403, 'Unauthorized: You do not have permission to delete roles.');
        }

        // Authorization: SuperAdmin can delete any role. Clinic Admin/Staff can only delete roles for their client.
        if (!$superAdminUser && $role->client_id !== $user->client_id) {
            abort(403, 'Unauthorized: You can only delete roles for your own client.');
        }

        $role->delete(); // Soft delete

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }


    // --- System-wide Permission Management (SuperAdmin Only) ---

    /**
     * Display a listing of system-wide permissions.
     *
     * @return \Illuminate\View\View
     */
    public function indexPermissions()
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can manage system permissions.');
        }

        $permissions = Permission::latest()->paginate(10);
        return view('superadmin-users.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new system-wide permission.
     *
     * @return \Illuminate\View\View
     */
    public function createPermission()
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can create system permissions.');
        }
        return view('superadmin-users.permissions.create');
    }

    /**
     * Store a newly created system-wide permission in storage.
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

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the specified system-wide permission.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\View\View
     */
    public function editPermission(Permission $permission)
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can edit system permissions.');
        }
        return view('superadmin-users.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified system-wide permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
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

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified system-wide permission from storage (soft delete).
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPermission(Permission $permission)
    {
        $superAdminUser = Auth::guard('superadmin')->user();
        if (!$superAdminUser) {
            abort(403, 'Unauthorized: Only SuperAdmins can delete system permissions.');
        }

        $permission->delete(); // Soft delete

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
