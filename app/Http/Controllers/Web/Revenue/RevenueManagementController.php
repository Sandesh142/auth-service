<?php

namespace App\Http\Controllers\Web\Revenue;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\RevenueEntry;
use App\Models\Admin\Client;
use App\Models\Admin\Branch;
use App\Models\Admin\ClientServiceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RevenueManagementController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:web'); // Ensure user is logged in
    //     // Authorization: SuperAdmin can manage all revenue. Admin/Staff can manage their client's revenue.
    //     $this->middleware(function ($request, $next) {
    //         $user = Auth::user();
    //         if ($user->role === 'superadmin') {
    //             // SuperAdmin has full access
    //         } elseif ($user->role === 'admin' || $user->role === 'staff') {
    //             // Admins/Staff can access revenue related to their client_id
    //             // Further permission checks can be added here (e.g., $user->hasPermission('view_revenue'))
    //         } else {
    //             abort(403, 'Unauthorized: You do not have permission to access revenue management.');
    //         }
    //         return $next($request);
    //     });
    // }

    public function __construct()
    {
        // This middleware ensures only logged-in users can access these routes.
        // It allows access if authenticated by 'web' OR 'superadmin' guard.
        $this->middleware(['auth:web', 'auth:superadmin']);
        $superAdminUser = Auth::guard('superadmin')->user();
        // print_r("superadmin" . $superAdminUser); die;
        // Authorization logic within the constructor to handle guard-specific access
        $this->middleware(function ($request, $next) {
            $webUser = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();

            // If a SuperAdmin is logged in, they have full access to revenue management
            if ($superAdminUser) {
                return $next($request); // SuperAdmin gets immediate access and proceeds
            }

            // If a web user (Clinic Admin/Staff) is logged in, apply permission checks
            if ($webUser) {
                // Only users with 'view_revenue' permission can access this module
                // This is the base permission for accessing any revenue related pages
                if (!$webUser->hasPermission('view_revenue')) {
                    abort(403, 'Unauthorized: You do not have permission to view revenue.');
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
     * Display a listing of revenue entries.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RevenueEntry::with('client', 'serviceType', 'branch', 'user');

        if ($user->role === 'superadmin') {
            // SuperAdmin can filter by any client
            if ($request->has('client_id')) {
                $query->where('revenue_entries.client_id', $request->client_id); // Qualify client_id
            }
            $clients = Client::all();
        } else {
            // Admin/Staff can only see their own client's revenue
            $query->where('revenue_entries.client_id', $user->client_id); // Qualify client_id
            $clients = Client::where('id', $user->client_id)->get(); // Only their client
        }

        // Apply filters
        if ($request->has('branch_id') && $request->branch_id != '') {
            $query->where('revenue_entries.branch_id', $request->branch_id); // Qualify branch_id
        }
        if ($request->has('service_type_id') && $request->service_type_id != '') {
            $query->where('revenue_entries.client_service_type_id', $request->service_type_id); // Qualify client_service_type_id
        }
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('revenue_entries.entry_date', '>=', $request->start_date); // Qualify entry_date
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('revenue_entries.entry_date', '<=', $request->end_date); // Qualify entry_date
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('revenue_entries.status', $request->status); // Qualify status
        }

        $revenueEntries = $query->latest('revenue_entries.entry_date')->paginate(10); // Qualify order by column

        // For filter dropdowns
        $branches = ($user->role === 'superadmin' && $request->has('client_id'))
                        ? Branch::where('client_id', $request->client_id)->get()
                        : (($user->role !== 'superadmin') ? Branch::where('client_id', $user->client_id)->get() : collect());

        $serviceTypes = ($user->role === 'superadmin' && $request->has('client_id'))
                            ? ClientServiceType::where('client_id', $request->client_id)->get()
                            : (($user->role !== 'superadmin') ? ClientServiceType::where('client_id', $user->client_id)->get() : collect());


        return view('admin-users.revenue.index', compact('revenueEntries', 'clients', 'branches', 'serviceTypes'));
    }

    /**
     * Show the form for creating a new revenue entry.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $client_id = $user->client_id;

        // SuperAdmin can choose client, Admin/Staff are restricted to their client
        $clients = ($user->role === 'superadmin') ? Client::all() : Client::where('id', $client_id)->get();
        $branches = ($client_id) ? Branch::where('client_id', $client_id)->get() : collect();
        $serviceTypes = ($client_id) ? ClientServiceType::where('client_id', $client_id)->get() : collect();

        return view('admin-users.revenue.create', compact('clients', 'branches', 'serviceTypes'));
    }

    /**
     * Store a newly created revenue entry in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_service_type_id' => [
                'required',
                Rule::exists('client_service_types', 'id')->where(function ($query) use ($request) {
                    $query->where('client_id', $request->client_id);
                }),
            ],
            'branch_id' => [
                'nullable',
                Rule::exists('branches', 'id')->where(function ($query) use ($request) {
                    $query->where('client_id', $request->client_id);
                }),
            ],
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string|max:1000',
            'entry_date' => 'required|date',
            'status' => 'required|in:completed,pending,cancelled',
        ]);

        // Authorization check: Ensure user is SuperAdmin OR the revenue belongs to their client_id
        if ($user->role !== 'superadmin' && $request->client_id !== $user->client_id) {
            abort(403, 'Unauthorized: You can only create revenue entries for your own client.');
        }

        RevenueEntry::create([
            'client_id' => $request->client_id,
            'client_service_type_id' => $request->client_service_type_id,
            'branch_id' => $request->branch_id,
            'user_id' => $user->id, // Record who created the entry
            'amount' => $request->amount,
            'currency' => $request->currency,
            'description' => $request->description,
            'entry_date' => $request->entry_date,
            'status' => $request->status,
        ]);

        return redirect()->route('revenue.index')
                         ->with('success', 'Revenue entry created successfully.');
    }

    /**
     * Display the specified revenue entry.
     *
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\View\View
     */
    public function show(RevenueEntry $revenueEntry)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $revenueEntry->client_id) {
            abort(403, 'Unauthorized: You can only view revenue entries for your own client.');
        }
        $revenueEntry->load('client', 'serviceType', 'branch', 'user');
        return view('admin-users.revenue.show', compact('revenueEntry'));
    }

    /**
     * Show the form for editing the specified revenue entry.
     *
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\View\View
     */
    public function edit(RevenueEntry $revenueEntry)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $revenueEntry->client_id) {
            abort(403, 'Unauthorized: You can only edit revenue entries for your own client.');
        }

        $client_id = $revenueEntry->client_id;
        $clients = ($user->role === 'superadmin') ? Client::all() : Client::where('id', $client_id)->get();
        $branches = Branch::where('client_id', $client_id)->get();
        $serviceTypes = ClientServiceType::where('client_id', $client_id)->get();

        return view('admin-users.revenue.edit', compact('revenueEntry', 'clients', 'branches', 'serviceTypes'));
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
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $revenueEntry->client_id) {
            abort(403, 'Unauthorized: You can only update revenue entries for your own client.');
        }

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_service_type_id' => [
                'required',
                Rule::exists('client_service_types', 'id')->where(function ($query) use ($request) {
                    $query->where('client_id', $request->client_id);
                }),
            ],
            'branch_id' => [
                'nullable',
                Rule::exists('branches', 'id')->where(function ($query) use ($request) {
                    $query->where('client_id', $request->client_id);
                }),
            ],
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string|max:1000',
            'entry_date' => 'required|date',
            'status' => 'required|in:completed,pending,cancelled',
        ]);

        // Authorization check again for the updated client_id if SuperAdmin changes it
        if ($user->role !== 'superadmin' && $request->client_id !== $user->client_id) {
            abort(403, 'Unauthorized: You can only update revenue entries for your own client.');
        }


        $revenueEntry->update($request->all());

        return redirect()->route('revenue.show', $revenueEntry->id)
                         ->with('success', 'Revenue entry updated successfully.');
    }

    /**
     * Remove the specified revenue entry from storage (soft delete).
     *
     * @param  \App\Models\RevenueEntry  $revenueEntry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(RevenueEntry $revenueEntry)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $revenueEntry->client_id) {
            abort(403, 'Unauthorized: You can only delete revenue entries for your own client.');
        }

        $revenueEntry->delete(); // Soft delete

        return redirect()->route('revenue.index')
                         ->with('success', 'Revenue entry deleted successfully.');
    }

    /**
     * Get a summary of revenue.
     * This method is called from the dashboard functions in web.php.
     *
     * @param Request $request
     * @return array
     */
    public function getRevenueSummary(Request $request): array
    {
        $user = null;
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('superadmin')->check()) {
            $user = Auth::guard('superadmin')->user();
        }

        $query = RevenueEntry::query();

        // Filter by client_id if a web user (Admin/Staff) is logged in
        if ($user && $user instanceof \App\Models\User && $user->role !== 'superadmin') {
            $query->where('revenue_entries.client_id', $user->client_id); // Qualify client_id
        } elseif ($user && $user instanceof \App\Models\SuperAdmin\SuperAdmin) {
            // If SuperAdmin is logged in, they see all revenue, so no client_id filter by default
            // However, if the request explicitly has a client_id filter (e.g., from a SuperAdmin filter form)
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('revenue_entries.client_id', $request->client_id);
            }
        } elseif ($request->has('client_id') && $request->client_id != '') {
            // This case handles when no user is logged in, but a client_id is provided in the request
            // (e.g., for public facing revenue reports if any, or if a superadmin explicitly filters)
            $query->where('revenue_entries.client_id', $request->client_id);
        }


        // Filter by date range for summary
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $query->whereBetween('revenue_entries.entry_date', [$startDate, $endDate]) // Qualify entry_date
                ->where('revenue_entries.status', 'completed'); // Qualify status

        $totalRevenue = $query->sum('amount');

        // Clone the query before adding joins for service type and branch,
        // to ensure the base query for totalRevenue is not affected.
        // Also, ensure we're selecting only from the revenue_entries table first
        // before joining to avoid ambiguous column errors on the base query.
        $baseQueryForJoins = RevenueEntry::whereBetween('revenue_entries.entry_date', [$startDate, $endDate])
                                            ->where('revenue_entries.status', 'completed');

        // Apply client filtering to the cloned query as well
        if ($user && $user instanceof \App\Models\User && $user->role !== 'superadmin') {
            $baseQueryForJoins->where('revenue_entries.client_id', $user->client_id);
        } elseif ($user && $user instanceof \App\Models\SuperAdmin\SuperAdmin) {
            if ($request->has('client_id') && $request->client_id != '') {
                $baseQueryForJoins->where('revenue_entries.client_id', $request->client_id);
            }
        } elseif ($request->has('client_id') && $request->client_id != '') {
            $baseQueryForJoins->where('revenue_entries.client_id', $request->client_id);
        }


        $revenueByServiceType = $baseQueryForJoins->clone()
                                        ->join('client_service_types', 'revenue_entries.client_service_type_id', '=', 'client_service_types.id')
                                        ->selectRaw('client_service_types.name as service_name, SUM(revenue_entries.amount) as total_amount')
                                        ->groupBy('client_service_types.name')
                                        ->get();

        $revenueByBranch = $baseQueryForJoins->clone()
                                        ->join('branches', 'revenue_entries.branch_id', '=', 'branches.id')
                                        ->selectRaw('branches.name as branch_name, SUM(revenue_entries.amount) as total_amount')
                                        ->groupBy('branches.name')
                                        ->get();

        return [
            'total_revenue' => $totalRevenue,
            'revenue_by_service_type' => $revenueByServiceType,
            'revenue_by_branch' => $revenueByBranch,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
