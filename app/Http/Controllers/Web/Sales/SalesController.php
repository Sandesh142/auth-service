<?php

namespace App\Http\Controllers\Web\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Sale;
use App\Models\Admin\SaleItem;
use App\Models\Admin\Medicine;
use App\Models\Admin\Batch;
use App\Models\Admin\Client;
use App\Models\Admin\ClientServiceType;
use App\Models\Admin\Branch;
use App\Models\RevenueEntry; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth:web'); // Ensure user is logged in
        // Authorization: Admin/Staff can create sales for their client. SuperAdmin can view all.
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if ($user->role === 'superadmin') {
                // SuperAdmin has full access to all sales
            } elseif ($user->role === 'admin' || $user->role === 'staff') {
                // Admins/Staff can access sales related to their client_id
                // Further permission checks can be added here (e.g., $user->hasPermission('create sales'))
            } else {
                abort(403, 'Unauthorized: You do not have permission to access sales.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of sales.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Sale::with('user', 'client', 'branch');

        if ($user->role === 'superadmin') {
            if ($request->has('client_id')) {
                $query->where('client_id', $request->client_id);
            }
            $clients = Client::all();
        } else {
            $query->where('client_id', $user->client_id);
            $clients = Client::where('id', $user->client_id)->get();
        }

        // Add filters (e.g., date, invoice number, user)
        if ($request->has('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $sales = $query->latest()->paginate(10);

        // For filtering by user, get users belonging to the current client or all for superadmin
        $usersForFilter = ($user->role === 'superadmin') ? \App\Models\User::all() : \App\Models\User::where('client_id', $user->client_id)->get();


        return view('admin-users.sales.index', compact('sales', 'clients', 'usersForFilter'));
    }

    /**
     * Show the form for creating a new sale (POS interface).
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        $client = ($user->role === 'superadmin') ? null : Client::find($user->client_id);
        $branches = ($user->role === 'superadmin') ? Branch::all() : Branch::where('client_id', $user->client_id)->get();

        // Pass available medicines for selection (only for the current client)
        $medicines = Medicine::where('client_id', $user->client_id)->where('is_active', true)->get();

        return view('admin-users.sales.create', compact('client', 'branches', 'medicines'));
    }

    /**
     * Store a newly created sale in storage.
     * This is a complex transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        Log::info('SalesController@store - Starting sale process.');
        Log::info('SalesController@store - Request data: ' . json_encode($request->all()));

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'branch_id' => 'nullable|exists:branches,id',
            'payment_method' => 'required|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.batch_id' => 'nullable|exists:batches,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0.01',
            'sub_total' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'change_due' => 'required|numeric',
        ]);

        if ($user->role !== 'superadmin' && $request->client_id != $user->client_id) {
            Log::warning('SalesController@store - Unauthorized attempt: Client ID mismatch. Request Client ID: ' . $request->client_id . ', User Client ID: ' . $user->client_id);
            abort(403, 'Unauthorized: You can only create sales for your own client.');
        }
        Log::info('SalesController@store - Client ID authorization passed.');

        if ($request->filled('branch_id')) {
            $branch = Branch::where('id', $request->branch_id)->where('client_id', $request->client_id)->first();
            if (!$branch) {
                Log::warning('SalesController@store - Branch ID ' . $request->branch_id . ' does not belong to client ID ' . $request->client_id);
                throw ValidationException::withMessages(['branch_id' => 'The selected branch does not belong to the chosen client.']);
            }
        }
        Log::info('SalesController@store - Branch ID check passed.');

        DB::beginTransaction();
        try {
            Log::info('SalesController@store - Database transaction started.');

            $sale = Sale::create([
                'client_id' => $request->client_id,
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
                'invoice_number' => Sale::generateInvoiceNumber(),
                'sub_total' => $request->sub_total,
                'discount_amount' => $request->discount_amount ?? 0,
                'tax_amount' => $request->tax_amount ?? 0,
                'total_amount' => $request->total_amount,
                'amount_paid' => $request->amount_paid,
                'change_due' => $request->change_due,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => 'completed',
            ]);
            Log::info('SalesController@store - Sale created with ID: ' . $sale->id . ', Invoice: ' . $sale->invoice_number);

            foreach ($request->items as $index => $itemData) {
                Log::info('SalesController@store - Processing item ' . $index . ': ' . json_encode($itemData));

                $medicine = Medicine::where('id', $itemData['medicine_id'])->where('client_id', $request->client_id)->firstOrFail();
                Log::info('SalesController@store - Found medicine: ' . $medicine->name);

                $batch = null;
                if ($itemData['batch_id']) {
                    $batch = Batch::where('id', $itemData['batch_id'])
                                  ->where('medicine_id', $medicine->id)
                                  ->where('client_id', $request->client_id)
                                  ->firstOrFail();
                    Log::info('SalesController@store - Found batch: ' . $batch->batch_number . ' for medicine ' . $medicine->name);

                    if ($batch->current_quantity < $itemData['quantity']) {
                        Log::error('SalesController@store - Insufficient stock for ' . $medicine->name . ' (Batch: ' . $batch->batch_number . '). Requested: ' . $itemData['quantity'] . ', Available: ' . $batch->current_quantity);
                        throw ValidationException::withMessages(['items' => 'Insufficient stock for ' . $medicine->name . ' (Batch: ' . $batch->batch_number . '). Available: ' . $batch->current_quantity]);
                    }
                    $batch->decrement('current_quantity', $itemData['quantity']);
                    Log::info('SalesController@store - Decremented stock for batch ' . $batch->batch_number . ' by ' . $itemData['quantity']);
                } else {
                    Log::info('SalesController@store - Batch ID not provided for medicine ' . $medicine->name . '. Attempting to decrement from available batches.');
                    $totalAvailable = $medicine->batches()->sum('current_quantity');
                    if ($totalAvailable < $itemData['quantity']) {
                        Log::error('SalesController@store - Insufficient total stock for ' . $medicine->name . '. Requested: ' . $itemData['quantity'] . ', Available: ' . $totalAvailable);
                        throw ValidationException::withMessages(['items' => 'Insufficient total stock for ' . $medicine->name . '. Available: ' . $totalAvailable]);
                    }

                    $remainingToDecrement = $itemData['quantity'];
                    $batchesToUse = $medicine->batches()->where('current_quantity', '>', 0)->orderBy('expiry_date', 'asc')->get();

                    foreach ($batchesToUse as $b) {
                        if ($remainingToDecrement <= 0) break;

                        $qtyToTake = min($remainingToDecrement, $b->current_quantity);
                        $b->decrement('current_quantity', $qtyToTake);
                        $remainingToDecrement -= $qtyToTake;
                        if (!$batch) $batch = $b;
                        Log::info('SalesController@store - Decremented stock for batch ' . $b->batch_number . ' by ' . $qtyToTake . '. Remaining to decrement: ' . $remainingToDecrement);
                    }
                }

                $sale->items()->create([
                    'medicine_id' => $medicine->id,
                    'batch_id' => $batch ? $batch->id : null,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'sub_total' => $itemData['quantity'] * $itemData['unit_price'],
                    'discount_amount' => $itemData['discount_amount'] ?? 0,
                    'tax_amount' => $itemData['tax_amount'] ?? 0,
                    'total_price' => ($itemData['quantity'] * $itemData['unit_price']) - ($itemData['discount_amount'] ?? 0) + ($itemData['tax_amount'] ?? 0),
                ]);
                Log::info('SalesController@store - Sale item added for medicine: ' . $medicine->name);
            }
            Log::info('SalesController@store - All sale items processed.');

            // --- START: Robust ClientServiceType Retrieval ---
            $clientServiceType = ClientServiceType::where('client_id', $sale->client_id)
                                                ->where('name', 'Pharmacy Sales')
                                                ->first(); // Use first() instead of firstOrFail()

            if (!$clientServiceType) {
                // Log the error for debugging
                Log::error('SalesController@store - ClientServiceType "Pharmacy Sales" not found for client ID: ' . $sale->client_id . '. Please create this service type via Client Management -> Service Types for the respective client.');
                // Throw a ValidationException so the error message is displayed nicely on the form
                throw ValidationException::withMessages([
                    'service_type' => 'The required service type "Pharmacy Sales" is not set up for your client. Please go to Client Management -> Service Types and create a service type named "Pharmacy Sales" for your client.'
                ]);
            }
            // --- END: Robust ClientServiceType Retrieval ---

            RevenueEntry::create([
                'client_id' => $sale->client_id,
                'client_service_type_id' => $clientServiceType->id, // Use the ID from the found service type
                'branch_id' => $sale->branch_id,
                'amount' => $sale->total_amount,
                'currency' => 'INR',
                'description' => 'Sale (Invoice: ' . $sale->invoice_number . ')',
                'entry_date' => $sale->created_at->toDateString(),
                'status' => 'completed',
            ]);
            Log::info('SalesController@store - Revenue entry created.');

            DB::commit();
            Log::info('SalesController@store - Database transaction committed successfully.');

            return redirect()->route('sales.show', $sale->id)
                             ->with('success', 'Sale completed successfully! Invoice: ' . $sale->invoice_number);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('SalesController@store - Validation Exception during sale: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SalesController@store - Sale Transaction Failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'An error occurred during the sale: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\View\View
     */
    public function show(Sale $sale)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $sale->client_id) {
            abort(403, 'Unauthorized: You can only view sales for your own client.');
        }

        $sale->load('items.medicine', 'items.batch', 'user', 'client', 'branch');

        return view('admin-users.sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified sale.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\View\View
     */
    public function edit(Sale $sale)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $sale->client_id) {
            abort(403, 'Unauthorized: You can only edit sales for your own client.');
        }

        // Note: Editing a completed sale can be complex due to stock adjustments.
        // For an MVP, you might restrict editing completed sales or only allow status changes.
        // If allowing full edit, you'd need to reverse stock changes and re-apply.
        // For simplicity, this example will just load the data.
        $sale->load('items.medicine', 'items.batch');
        $branches = ($user->role === 'superadmin') ? Branch::all() : Branch::where('client_id', $user->client_id)->get();
        $medicines = Medicine::where('client_id', $user->client_id)->where('is_active', true)->get();


        return view('admin-users.sales.edit', compact('sale', 'branches', 'medicines'));
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
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $sale->client_id) {
            abort(403, 'Unauthorized: You can only update sales for your own client.');
        }

        // For simplicity, we'll only allow status update and notes for existing sales.
        // Full item-level updates are complex and require careful stock reversal/re-decrement logic.
        $request->validate([
            'payment_method' => 'sometimes|required|string|max:50',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:completed,pending,cancelled', // Allow changing status
        ]);

        // If status changes from completed to cancelled, you might need to restock items
        if ($sale->status === 'completed' && $request->status === 'cancelled') {
            DB::beginTransaction();
            try {
                foreach ($sale->items as $item) {
                    if ($item->batch_id) {
                        Batch::find($item->batch_id)->increment('current_quantity', $item->quantity);
                    } else {
                        // Handle non-batch-specific restock if applicable
                    }
                }
                // Also delete or mark the corresponding RevenueEntry as cancelled
                RevenueEntry::where('client_id', $sale->client_id)
                            ->where('description', 'like', 'Sale (Invoice: ' . $sale->invoice_number . ')%')
                            ->update(['status' => 'cancelled']);

                $sale->update($request->only('payment_method', 'notes', 'status'));
                DB::commit();
                return redirect()->route('sales.show', $sale->id)
                                 ->with('success', 'Sale updated and stock restocked successfully!');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Sale Cancellation Restock Failed: ' . $e->getMessage());
                return back()->with('error', 'Failed to update sale and restock. Please try again.')->withInput();
            }
        }

        $sale->update($request->only('payment_method', 'notes', 'status'));

        return redirect()->route('sales.show', $sale->id)
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
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $user->client_id !== $sale->client_id) {
            abort(403, 'Unauthorized: You can only delete sales for your own client.');
        }

        DB::beginTransaction();
        try {
            // Restock items before deleting the sale
            foreach ($sale->items as $item) {
                if ($item->batch_id) {
                    Batch::find($item->batch_id)->increment('current_quantity', $item->quantity);
                } else {
                    // Handle non-batch-specific restock if applicable
                }
            }
            // Delete corresponding RevenueEntry
            RevenueEntry::where('client_id', $sale->client_id)
                        ->where('description', 'like', 'Sale (Invoice: ' . $sale->invoice_number . ')%')
                        ->delete(); // Or softDelete if you use softDeletes on RevenueEntry

            $sale->delete(); // Soft delete the sale

            DB::commit();
            return redirect()->route('sales.index')
                             ->with('success', 'Sale deleted and stock restocked successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sale Deletion Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete sale and restock. Please try again.');
        }
    }

    /**
     * API endpoint to search medicines by name or barcode for POS.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function searchMedicinesForSale(Request $request)
    // {
    //     print_r( $request->all()); die;
    //     $user = Auth::user();
    //     $searchTerm = $request->query('query');

    //     if (!$searchTerm) {
    //         return response()->json([]);
    //     }

    //     $medicines = Medicine::where('client_id', $user->client_id)
    //                          ->where('is_active', true)
    //                          ->where(function ($query) use ($searchTerm) {
    //                              $query->where('name', 'like', '%' . $searchTerm . '%')
    //                                    ->orWhere('barcode', $searchTerm);
    //                          })
    //                          ->with(['batches' => function($q) {
    //                              $q->where('current_quantity', '>', 0)
    //                                ->where('expiry_date', '>', now()->toDateString()) // Only non-expired stock
    //                                ->orderBy('expiry_date', 'asc'); // Prioritize earliest expiry
    //                          }])
    //                          ->get();

    //     // Format for frontend
    //     $results = $medicines->map(function ($medicine) {
    //         return [
    //             'id' => $medicine->id,
    //             'name' => $medicine->name,
    //             'brand' => $medicine->brand,
    //             'strength' => $medicine->strength,
    //             'unit' => $medicine->unit,
    //             'barcode' => $medicine->barcode,
    //             'total_stock' => $medicine->totalStock(),
    //             'batches' => $medicine->batches->map(function($batch) {
    //                 return [
    //                     'id' => $batch->id,
    //                     'batch_number' => $batch->batch_number,
    //                     'expiry_date' => $batch->expiry_date->format('Y-m-d'),
    //                     'current_quantity' => $batch->current_quantity,
    //                     'cost_price' => $batch->cost_price,
    //                     // You might add a 'sale_price' here if it's batch-specific
    //                 ];
    //             })
    //         ];
    //     });

    //     return response()->json($results);
    // }

    public function searchMedicinesForSale(Request $request)
    {
        // Removed print_r and die to allow JSON response
        $user = Auth::user();
        $searchTerm = $request->query('query');

        if (!$searchTerm) {
            return response()->json([]);
        }

        $medicines = Medicine::where('client_id', $user->client_id)
                             ->where('is_active', true)
                             ->where(function ($query) use ($searchTerm) {
                                 $query->where('name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('barcode', $searchTerm);
                             })
                             ->with(['batches' => function($q) {
                                 $q->where('current_quantity', '>', 0)
                                   ->where('expiry_date', '>', Carbon::today()->toDateString()) // Only non-expired stock
                                   ->orderBy('expiry_date', 'asc'); // Prioritize earliest expiry
                             }])
                             ->whereHas('batches', function($q) {
                                 // Ensure the medicine has at least one valid batch
                                 $q->where('current_quantity', '>', 0)
                                   ->where('expiry_date', '>', Carbon::today()->toDateString());
                             })
                             ->limit(10) // Limit results for performance
                             ->get();

        // Format for frontend
        $results = $medicines->map(function ($medicine) {
            // Calculate total stock from filtered batches for display
            $totalStock = $medicine->batches->sum('current_quantity');

            return [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'brand' => $medicine->brand,
                'strength' => $medicine->strength,
                'unit' => $medicine->unit,
                'barcode' => $medicine->barcode,
                'total_stock' => $totalStock, // Use the calculated total stock
                'batches' => $medicine->batches->map(function($batch) {
                    return [
                        'id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'expiry_date' => $batch->expiry_date->format('Y-m-d'),
                        'current_quantity' => $batch->current_quantity,
                        'cost_price' => $batch->cost_price,
                        // You might add a 'sale_price' here if it's batch-specific
                    ];
                })
            ];
        });

        return response()->json($results);
    }

}
