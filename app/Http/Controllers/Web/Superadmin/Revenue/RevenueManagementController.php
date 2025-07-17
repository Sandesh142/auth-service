<?php

namespace App\Http\Controllers\web\Superadmin\Revenue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RevenueEntry;
use App\Models\Admin\Client;
use App\Models\User;
use App\Models\SuperAdmin\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Branch; 
use App\Models\Admin\ServiceType;

class RevenueManagementController extends Controller
{
    // public function __construct()
    // {
    //     // This middleware applies to all methods in this controller.
    //     // It will differentiate authorization based on the route's prefix.
    //     $this->middleware(function ($request, $next) {
    //         $routeName = $request->route()->getName();

    //         // --- Authorization for SuperAdmin routes (e.g., superadmin.revenue.*) ---
    //         if (str_starts_with($routeName, 'superadmin.revenue.')) {
    //             $superAdminUser = Auth::guard('superadmin')->user();

    //             // If not authenticated as SuperAdmin, or if a web user somehow accesses this prefix, deny.
    //             if (!$superAdminUser) {
    //                 // This should theoretically be caught by 'auth:superadmin' on the route group,
    //                 // but it acts as an explicit final gate.
    //                 abort(403, 'Unauthorized: Only SuperAdmins can access these revenue features.');
    //             }
    //             // SuperAdmin is authenticated and on a SuperAdmin route, grant full access.
    //             return $next($request);
    //         }

    //         // --- Authorization for Admin/Staff routes (e.g., admin.revenue.*) ---
    //         elseif (str_starts_with($routeName, 'admin.revenue.')) {
    //             $webUser = Auth::guard('web')->user();

    //             // If not authenticated as a web user, deny.
    //             if (!$webUser) {
    //                 // This should theoretically be caught by 'auth:web' on the route group.
    //                 abort(403, 'Unauthorized: Only Clinic Admins/Staff can access these revenue features.');
    //             }

    //             // Apply granular permission checks for web users
    //             if (!$webUser->hasPermission('view_revenue')) {
    //                 abort(403, 'Unauthorized: You do not have permission to view revenue.');
    //             }
    //             if ((str_contains($routeName, 'create') || str_contains($routeName, 'store')) && !$webUser->hasPermission('create_revenue')) {
    //                 abort(403, 'Unauthorized: You do not have permission to create revenue entries.');
    //             }
    //             if ((str_contains($routeName, 'edit') || str_contains($routeName, 'update')) && !$webUser->hasPermission('edit_revenue')) {
    //                 abort(403, 'Unauthorized: You do not have permission to edit revenue entries.');
    //             }
    //             if (str_contains($routeName, 'destroy') && !$webUser->hasPermission('delete_revenue')) {
    //                 abort(403, 'Unauthorized: You do not have permission to delete revenue entries.');
    //             }
    //             // Web user is authenticated and has permissions, allow.
    //             return $next($request);
    //         }

    //         // --- Fallback for any other unexpected route access ---
    //         // This block should ideally not be hit if routes are correctly defined and prefixed.
    //         abort(403, 'Unauthorized access to revenue management.');

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
     * Helper to determine the correct view prefix.
     *
     * @return string
     */
    protected function getViewPrefix(): string
    {
        return Auth::guard('superadmin')->check() ? 'superadmin-users.revenue.' : 'admin-users.revenue.';
    }

    /**
     * Display a listing of revenue entries.
     * SuperAdmin sees all entries. Admin/Staff see entries within their client.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = RevenueEntry::query();
        $clients = collect();
        $branches = collect(); // Always initialize
        $serviceTypes = collect(); // Always initialize

        if ($superAdminUser) {
            // SuperAdmin can filter by client
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
                // If client is selected, filter branches and service types for that client
                $branches = Branch::where('client_id', $request->client_id)->get();
                $serviceTypes = ServiceType::where('client_id', $request->client_id)->get();
            } else {
                // If no client selected, SuperAdmin sees all clients, but branches/service types
                // for filter should only be populated if a client is chosen.
                // So, $branches and $serviceTypes remain empty collections here for SuperAdmin.
            }
            $clients = Client::all(); // For SuperAdmin to filter
        } elseif ($webUser) {
            // Admin/Staff can only see revenue entries belonging to their client
            $query->where('client_id', $webUser->client_id);
            $clients = Client::where('id', $webUser->client_id)->get(); // Only their client
            // For Admin, always load their client's branches and service types
            $branches = Branch::where('client_id', $webUser->client_id)->get();
            $serviceTypes = ServiceType::where('client_id', $webUser->client_id)->get();
        } else {
            // This case should ideally not be hit due to route middleware, but as a fallback
            abort(403, 'Unauthorized: No authenticated user found.');
        }

        // Apply filters for branch_id, service_type_id, start_date, end_date, status
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->has('service_type_id') && $request->service_type_id != '') {
            $query->where('service_type_id', $request->service_type_id);
        }
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('entry_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('entry_date', '<=', $request->end_date);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Eager load relationships for display in the table
        $revenueEntries = $query->with('client', 'branch', 'serviceType')->latest('entry_date')->paginate(10);

        // Ensure all variables are passed to the view
        return view($this->getViewPrefix() . 'index', compact('revenueEntries', 'clients', 'branches', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new revenue entry.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_revenue'))) {
            abort(403, 'Unauthorized: You do not have permission to create revenue entries.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view($this->getViewPrefix() . 'create', compact('clients'));
    }

    /**
     * Store a newly created revenue entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_revenue'))) {
            abort(403, 'Unauthorized: You do not have permission to store revenue entries.');
        }

        $rules = [
            'entry_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:sale,service,other',
            'description' => 'nullable|string|max:1000',
        ];

        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'required|exists:clients,id';
            $clientIdForValidation = $request->client_id;
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
        } else {
            abort(403, 'Unauthorized: No authenticated user to store revenue entries.');
        }

        $request->validate($rules);

        $revenueEntry = RevenueEntry::create($request->all());

        $redirectRouteName = $superAdminUser ? 'superadmin.revenue.show' : 'admin.revenue.show';
        return redirect()->route($redirectRouteName, $revenueEntry->id)
                         ->with('success', 'Revenue entry created successfully!');
    }

    /**
     * Display the specified revenue entry.
     *
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\View\View
     */
    public function show(RevenueEntry $revenueEntry)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $revenueEntry->client_id)) {
            abort(403, 'Unauthorized: You can only view revenue entries for your own client or you lack permission.');
        }

        return view($this->getViewPrefix() . 'show', compact('revenueEntry'));
    }

    /**
     * Show the form for editing the specified revenue entry.
     *
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\View\View
     */
    public function edit(RevenueEntry $revenueEntry)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $revenueEntry->client_id || !$webUser->hasPermission('edit_revenue'))) {
            abort(403, 'Unauthorized: You do not have permission to edit revenue entries.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view($this->getViewPrefix() . 'edit', compact('revenueEntry', 'clients'));
    }

    /**
     * Update the specified revenue entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, RevenueEntry $revenueEntry)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $revenueEntry->client_id || !$webUser->hasPermission('edit_revenue'))) {
            abort(403, 'Unauthorized: You do not have permission to update revenue entries.');
        }

        $rules = [
            'entry_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:sale,service,other',
            'description' => 'nullable|string|max:1000',
        ];

        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'required|exists:clients,id';
            $clientIdForValidation = $request->client_id;
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
            if ($request->client_id !== $revenueEntry->client_id) {
                abort(403, 'Unauthorized: You cannot change the client for this revenue entry.');
            }
        } else {
            abort(403, 'Unauthorized: No authenticated user to update revenue entries.');
        }

        $request->validate($rules);

        $revenueEntry->update($request->all());

        $redirectRouteName = $superAdminUser ? 'superadmin.revenue.show' : 'admin.revenue.show';
        return redirect()->route($redirectRouteName, $revenueEntry->id)
                         ->with('success', 'Revenue entry updated successfully!');
    }

    /**
     * Remove the specified revenue entry from storage.
     *
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(RevenueEntry $revenueEntry)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $revenueEntry->client_id || !$webUser->hasPermission('delete_revenue'))) {
            abort(403, 'Unauthorized: You do not have permission to delete revenue entries.');
        }

        $revenueEntry->delete();

        $redirectRouteName = $superAdminUser ? 'superadmin.revenue.index' : 'admin.revenue.index';
        return redirect()->route($redirectRouteName)
                         ->with('success', 'Revenue entry deleted successfully!');
    }

    /**
     * Get a summary of revenue.
     * This method is called from dashboards.
     *
     * @param Request $request
     * @return array
     */
    public function getRevenueSummary(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = RevenueEntry::query();

        if ($superAdminUser) {
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            }
        } elseif ($webUser) {
            $query->where('client_id', $webUser->client_id);
        } else {
            return [
                'total_revenue' => 0,
                'revenue_by_type' => [],
                'revenue_last_7_days' => [],
            ];
        }

        $totalRevenue = $query->sum('amount');
        $revenueByType = $query->select('type', DB::raw('SUM(amount) as total'))
                               ->groupBy('type')
                               ->pluck('total', 'type');

        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();
        $revenueLast7Days = $query->where('entry_date', '>=', $sevenDaysAgo)
                                  ->orderBy('entry_date')
                                  ->get()
                                  ->groupBy(function($date) {
                                      return Carbon::parse($date->entry_date)->format('Y-m-d');
                                  })
                                  ->map(function ($day) {
                                      return $day->sum('amount');
                                  });

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates->put($date, $revenueLast7Days->get($date, 0));
        }

        return [
            'total_revenue' => $totalRevenue,
            'revenue_by_type' => $revenueByType->toArray(), // Ensure toArray for consistency
            'revenue_last_7_days' => $dates->toArray(),
        ];
    }
}