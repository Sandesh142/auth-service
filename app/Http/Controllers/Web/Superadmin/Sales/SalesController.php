<?php

namespace App\Http\Controllers\web\Superadmin\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Sale; // Assuming your Sale model
use App\Models\Admin\SaleItem; // Assuming your SaleItem model
use App\Models\Admin\Medicine;
use App\Models\Admin\Batch;
use App\Models\Admin\Client;
use App\Models\User;
use App\Models\SuperAdmin\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalesController extends Controller
{
    // public function __construct()
    // {
    //     // Middleware for SuperAdmin routes
    //     $this->middleware('auth:superadmin')->only([
    //         'index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'searchMedicinesForSale'
    //     ]);

    //     // Middleware for Admin routes
    //     $this->middleware('auth:web')->except([]); // All methods are covered by permissions

    //     // Authorization logic within the constructor
    //     $this->middleware(function ($request, $next) {
    //         $webUser = Auth::guard('web')->user();
    //         $superAdminUser = Auth::guard('superadmin')->user();

    //         if ($superAdminUser) {
    //             return $next($request);
    //         }

    //         if ($webUser) {
    //             $routeName = $request->route()->getName();
    //             if (str_starts_with($routeName, 'admin.sales.')) { // Ensure it's an admin-prefixed sales route
    //                 if (!$webUser->hasPermission('view_sales')) {
    //                     abort(403, 'Unauthorized: You do not have permission to view sales.');
    //                 }
    //                 if ((str_contains($routeName, 'create') || str_contains($routeName, 'store')) && !$webUser->hasPermission('create_sales')) {
    //                     abort(403, 'Unauthorized: You do not have permission to create sales.');
    //                 }
    //                 if ((str_contains($routeName, 'edit') || str_contains($routeName, 'update')) && !$webUser->hasPermission('edit_sales')) {
    //                     abort(403, 'Unauthorized: You do not have permission to edit sales.');
    //                 }
    //                 if (str_contains($routeName, 'destroy') && !$webUser->hasPermission('delete_sales')) {
    //                     abort(403, 'Unauthorized: You do not have permission to delete sales.');
    //                 }
    //             } else {
    //                 abort(403, 'Unauthorized access.');
    //             }
    //         } else {
    //             abort(403, 'Unauthorized access.');
    //         }

    //         return $next($request);
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
        return Auth::guard('superadmin')->check() ? 'superadmin-users.sales.' : 'admin-users.sales.';
    }

    /**
     * Display a listing of sales.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = Sale::query();
        $clients = collect();

        if ($superAdminUser) {
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            }
            $clients = Client::all();
        } elseif ($webUser) {
            $query->where('client_id', $webUser->client_id);
            $clients = Client::where('id', $webUser->client_id)->get();
        } else {
            abort(403, 'Unauthorized: No authenticated user found.');
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        $sales = $query->with('client', 'saleItems.medicine')->latest('sale_date')->paginate(10);

        return view($this->getViewPrefix() . 'index', compact('sales', 'clients'));
    }

    /**
     * Show the form for creating a new sale.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_sales'))) {
            abort(403, 'Unauthorized: You do not have permission to create sales.');
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
     * Store a newly created sale in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_sales'))) {
            abort(403, 'Unauthorized: You do not have permission to store sales.');
        }

        $rules = [
            'client_id' => 'required|exists:clients,id',
            'sale_date' => 'required|date|before_or_equal:today',
            'invoice_number' => 'required|string|max:255|unique:sales,invoice_number',
            'customer_name' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0|lte:total_amount',
            'payment_method' => 'required|string|in:cash,card,upi,other',
            'sale_items' => 'required|array|min:1',
            'sale_items.*.medicine_id' => 'required|exists:medicines,id',
            'sale_items.*.batch_id' => 'required|exists:batches,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0',
            'sale_items.*.sub_total' => 'required|numeric|min:0',
        ];

        if ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $webUser, $superAdminUser) {
            $clientId = $superAdminUser ? $request->client_id : $webUser->client_id;

            $sale = Sale::create([
                'client_id' => $clientId,
                'sale_date' => $request->sale_date,
                'invoice_number' => $request->invoice_number,
                'customer_name' => $request->customer_name,
                'total_amount' => $request->total_amount,
                'discount_amount' => $request->discount_amount ?? 0,
                'final_amount' => $request->final_amount,
                'payment_method' => $request->payment_method,
                'user_id' => $webUser->id ?? null, // Record who made the sale
            ]);

            foreach ($request->sale_items as $item) {
                $batch = Batch::find($item['batch_id']);
                if (!$batch || $batch->current_quantity < $item['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'sale_items' => 'Insufficient stock for medicine ' . ($batch->medicine->name ?? 'N/A') . ' in batch ' . ($batch->batch_number ?? 'N/A') . '.',
                    ]);
                }

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['medicine_id'],
                    'batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'sub_total' => $item['sub_total'],
                ]);

                // Deduct quantity from batch
                $batch->current_quantity -= $item['quantity'];
                $batch->save();
            }

            // Create a revenue entry for the sale
            RevenueEntry::create([
                'client_id' => $clientId,
                'entry_date' => $request->sale_date,
                'amount' => $request->final_amount,
                'type' => 'sale',
                'description' => 'Sale invoice ' . $request->invoice_number,
            ]);
        });


        $redirectRouteName = $superAdminUser ? 'superadmin.sales.index' : 'admin.sales.index';
        return redirect()->route($redirectRouteName)
                         ->with('success', 'Sale recorded successfully!');
    }

    /**
     * Display the specified sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\View\View
     */
    public function show(Sale $sale)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $sale->client_id)) {
            abort(403, 'Unauthorized: You can only view sales for your own client.');
        }

        $sale->load('client', 'saleItems.medicine', 'saleItems.batch');
        return view($this->getViewPrefix() . 'show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\View\View
     */
    public function edit(Sale $sale)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $sale->client_id || !$webUser->hasPermission('edit_sales'))) {
            abort(403, 'Unauthorized: You do not have permission to edit sales.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view($this->getViewPrefix() . 'edit', compact('sale', 'clients'));
    }

    /**
     * Update the specified sale in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Sale $sale)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $sale->client_id || !$webUser->hasPermission('edit_sales'))) {
            abort(403, 'Unauthorized: You do not have permission to update sales.');
        }

        $rules = [
            'client_id' => 'required|exists:clients,id',
            'sale_date' => 'required|date|before_or_equal:today',
            'invoice_number' => ['required', 'string', 'max:255', Rule::unique('sales', 'invoice_number')->ignore($sale->id)],
            'customer_name' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_amount' => 'required|numeric|min:0|lte:total_amount',
            'payment_method' => 'required|string|in:cash,card,upi,other',
            // For simplicity, we're not handling sale_items updates here,
            // as it requires complex logic for stock adjustments (add back old, deduct new)
            // This would typically be handled by creating a new sale or a return/refund process.
        ];

        if ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            if ($request->client_id !== $sale->client_id) {
                abort(403, 'Unauthorized: You cannot change the client for this sale.');
            }
        }

        $request->validate($rules);

        $sale->update($request->only([
            'client_id', 'sale_date', 'invoice_number', 'customer_name',
            'total_amount', 'discount_amount', 'final_amount', 'payment_method'
        ]));

        $redirectRouteName = $superAdminUser ? 'superadmin.sales.show' : 'admin.sales.show';
        return redirect()->route($redirectRouteName, $sale->id)
                         ->with('success', 'Sale updated successfully!');
    }

    /**
     * Remove the specified sale from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Sale $sale)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $sale->client_id || !$webUser->hasPermission('delete_sales'))) {
            abort(403, 'Unauthorized: You do not have permission to delete sales.');
        }

        DB::transaction(function () use ($sale) {
            // Revert stock quantities for each sale item
            foreach ($sale->saleItems as $item) {
                $batch = Batch::find($item->batch_id);
                if ($batch) {
                    $batch->current_quantity += $item->quantity;
                    $batch->save();
                }
                $item->delete(); // Delete sale item
            }
            // Delete associated revenue entry (assuming one-to-one or similar)
            // This might need more specific logic if revenue entries are aggregated differently
            RevenueEntry::where('description', 'Sale invoice ' . $sale->invoice_number)
                        ->where('client_id', $sale->client_id)
                        ->where('amount', $sale->final_amount)
                        ->delete(); // Simple delete, might need more robust matching

            $sale->delete();
        });

        $redirectRouteName = $superAdminUser ? 'superadmin.sales.index' : 'admin.sales.index';
        return redirect()->route($redirectRouteName)
                         ->with('success', 'Sale deleted successfully!');
    }

    /**
     * Search medicines for sale.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchMedicinesForSale(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('view_sales'))) { // Assuming view_sales allows searching medicines for sale
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $search = $request->get('query');
        $clientId = $superAdminUser ? $request->get('client_id') : ($webUser->client_id ?? null);

        if (!$clientId) {
            return response()->json(['error' => 'Client ID is required for medicine search.'], 400);
        }

        $medicines = Medicine::where('client_id', $clientId)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('barcode', 'like', '%' . $search . '%');
            })
            ->with(['batches' => function ($query) {
                $query->where('current_quantity', '>', 0)
                      ->where('expiry_date', '>=', Carbon::today())
                      ->orderBy('expiry_date', 'asc');
            }])
            ->get()
            ->filter(function ($medicine) {
                return $medicine->batches->isNotEmpty(); // Only return medicines with available batches
            })
            ->map(function ($medicine) {
                return [
                    'id' => $medicine->id,
                    'name' => $medicine->name,
                    'brand' => $medicine->brand,
                    'strength' => $medicine->strength,
                    'unit' => $medicine->unit,
                    'barcode' => $medicine->barcode,
                    'batches' => $medicine->batches->map(function ($batch) {
                        return [
                            'id' => $batch->id,
                            'batch_number' => $batch->batch_number,
                            'current_quantity' => $batch->current_quantity,
                            'cost_price' => $batch->cost_price, // Include cost price for calculations
                            'expiry_date' => Carbon::parse($batch->expiry_date)->format('Y-m-d'),
                        ];
                    }),
                ];
            });

        return response()->json($medicines);
    }
}
