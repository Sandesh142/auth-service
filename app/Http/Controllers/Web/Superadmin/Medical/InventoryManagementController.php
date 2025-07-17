<?php

namespace App\Http\Controllers\web\Superadmin\Medical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Medicine;
use App\Models\Admin\Batch;
use App\Models\Admin\Client;
use App\Models\User;
use App\Models\SuperAdmin\SuperAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class InventoryManagementController extends Controller
{
    // public function __construct()
    // {
    //     // Middleware for SuperAdmin routes
    //     $this->middleware('auth:superadmin')->only([
    //         'indexMedicines', 'createMedicine', 'storeMedicine', 'showMedicine', 'editMedicine', 'updateMedicine', 'destroyMedicine',
    //         'createBatch', 'storeBatch', 'editBatch', 'updateBatch', 'destroyBatch'
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

    //             // Medicine permissions
    //             if (str_starts_with($routeName, 'admin.medicines.')) {
    //                 if (!$webUser->hasPermission('view_medicines')) {
    //                     abort(403, 'Unauthorized: You do not have permission to view medicines.');
    //                 }
    //                 if ((str_contains($routeName, 'create') || str_contains($routeName, 'store')) && !$webUser->hasPermission('create_medicines')) {
    //                     abort(403, 'Unauthorized: You do not have permission to create medicines.');
    //                 }
    //                 if ((str_contains($routeName, 'edit') || str_contains($routeName, 'update')) && !$webUser->hasPermission('edit_medicines')) {
    //                     abort(403, 'Unauthorized: You do not have permission to edit medicines.');
    //                 }
    //                 if (str_contains($routeName, 'destroy') && !$webUser->hasPermission('delete_medicines')) {
    //                     abort(403, 'Unauthorized: You do not have permission to delete medicines.');
    //                 }
    //             }
    //             // Batch permissions
    //             elseif (str_starts_with($routeName, 'admin.batches.')) {
    //                 // Ensure medicine belongs to client for batch operations
    //                 $medicine = $request->route('medicine');
    //                 if ($medicine && $medicine->client_id !== $webUser->client_id) {
    //                     abort(403, 'Unauthorized: You can only manage batches for your own client\'s medicines.');
    //                 }
    //                 if ((str_contains($routeName, 'create') || str_contains($routeName, 'store')) && !$webUser->hasPermission('create_batches')) {
    //                     abort(403, 'Unauthorized: You do not have permission to create batches.');
    //                 }
    //                 if ((str_contains($routeName, 'edit') || str_contains($routeName, 'update')) && !$webUser->hasPermission('edit_batches')) {
    //                     abort(403, 'Unauthorized: You do not have permission to edit batches.');
    //                 }
    //                 if (str_contains($routeName, 'destroy') && !$webUser->hasPermission('delete_batches')) {
    //                     abort(403, 'Unauthorized: You do not have permission to delete batches.');
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
        return Auth::guard('superadmin')->check() ? 'superadmin-users.inventory.' : 'admin-users.inventory.';
    }

    /**
     * Display a listing of medicines.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexMedicines(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = Medicine::with('batches');
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
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('brand', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        $medicines = $query->paginate(10);

        return view($this->getViewPrefix() . 'medicines.index', compact('medicines', 'clients'));
    }

    /**
     * Show the form for creating a new medicine.
     *
     * @return \Illuminate\View\View
     */
    public function createMedicine()
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_medicines'))) {
            abort(403, 'Unauthorized: You do not have permission to create medicines.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view($this->getViewPrefix() . 'medicines.create', compact('clients'));
    }

    /**
     * Store a newly created medicine in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeMedicine(Request $request)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_medicines'))) {
            abort(403, 'Unauthorized: You do not have permission to store medicines.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'barcode' => 'nullable|string|max:255|unique:medicines,barcode',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'required|exists:clients,id';
            $clientIdForValidation = $request->client_id;
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
        } else {
            abort(403, 'Unauthorized: No authenticated user to store medicines.');
        }

        $rules['name'] = array_merge($rules['name'], [
            Rule::unique('medicines')->where(function ($query) use ($clientIdForValidation) {
                return $query->where('client_id', $clientIdForValidation);
            }),
        ]);

        $request->validate($rules);

        $medicine = Medicine::create($request->all());

        $redirectRouteName = $superAdminUser ? 'superadmin.medicines.show' : 'admin.medicines.show';
        return redirect()->route($redirectRouteName, $medicine->id)
                         ->with('success', 'Medicine added successfully!');
    }

    /**
     * Display the specified medicine and its batches.
     *
     * @param  \App\Models\Admin\Medicine  $medicine
     * @return \Illuminate\View\View
     */
    public function showMedicine(Medicine $medicine)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $medicine->client_id)) {
            abort(403, 'Unauthorized: You can only view medicines for your own client or you lack permission.');
        }

        $batches = $medicine->batches()->latest('expiry_date')->paginate(10);

        return view($this->getViewPrefix() . 'medicines.show', compact('medicine', 'batches'));
    }

    /**
     * Show the form for editing the specified medicine.
     *
     * @param  \App\Models\Admin\Medicine  $medicine
     * @return \Illuminate\View\View
     */
    public function editMedicine(Medicine $medicine)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $medicine->client_id || !$webUser->hasPermission('edit_medicines'))) {
            abort(403, 'Unauthorized: You do not have permission to edit medicines.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view($this->getViewPrefix() . 'medicines.edit', compact('medicine', 'clients'));
    }

    /**
     * Update the specified medicine in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Medicine  $medicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateMedicine(Request $request, Medicine $medicine)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $medicine->client_id || !$webUser->hasPermission('edit_medicines'))) {
            abort(403, 'Unauthorized: You do not have permission to update medicines.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:50',
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('medicines')->ignore($medicine->id)],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'required|exists:clients,id';
            $clientIdForValidation = $request->client_id;
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
            if ($request->client_id !== $medicine->client_id) {
                abort(403, 'Unauthorized: You cannot change the client for this medicine.');
            }
        } else {
            abort(403, 'Unauthorized: No authenticated user to update medicines.');
        }

        $rules['name'] = array_merge($rules['name'], [
            Rule::unique('medicines')->where(function ($query) use ($clientIdForValidation) {
                return $query->where('client_id', $clientIdForValidation);
            })->ignore($medicine->id),
        ]);

        $request->validate($rules);

        $medicine->update($request->all());

        $redirectRouteName = $superAdminUser ? 'superadmin.medicines.show' : 'admin.medicines.show';
        return redirect()->route($redirectRouteName, $medicine->id)
                         ->with('success', 'Medicine updated successfully!');
    }

    /**
     * Remove the specified medicine from storage.
     *
     * @param  \App\Models\Admin\Medicine  $medicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyMedicine(Medicine $medicine)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $medicine->client_id || !$webUser->hasPermission('delete_medicines'))) {
            abort(403, 'Unauthorized: You do not have permission to delete medicines.');
        }

        if ($medicine->batches()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete medicine: It has associated batches. Delete batches first.']);
        }
        $medicine->delete();

        $redirectRouteName = $superAdminUser ? 'superadmin.medicines.index' : 'admin.medicines.index';
        return redirect()->route($redirectRouteName)
                         ->with('success', 'Medicine deleted successfully!');
    }

    // --- Batch Management (nested under Medicine) ---

    /**
     * Show the form for creating a new batch for a specific medicine.
     *
     * @param  \App\Models\Admin\Medicine  $medicine
     * @return \Illuminate\View\View
     */
    public function createBatch(Medicine $medicine)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $medicine->client_id || !$webUser->hasPermission('create_batches'))) {
            abort(403, 'Unauthorized: You do not have permission to add batches for your own client\'s medicines.');
        }
        return view($this->getViewPrefix() . 'batches.create', compact('medicine'));
    }

    /**
     * Store a newly created batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Medicine  $medicine
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBatch(Request $request, Medicine $medicine)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $medicine->client_id || !$webUser->hasPermission('create_batches'))) {
            abort(403, 'Unauthorized: You do not have permission to store batches for your own client\'s medicines.');
        }

        $request->validate([
            'batch_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('batches')->where(function ($query) use ($medicine) {
                    return $query->where('medicine_id', $medicine->id)
                                 ->where('client_id', $medicine->client_id);
                }),
            ],
            'manufacture_date' => 'nullable|date|before_or_equal:today',
            'expiry_date' => 'required|date|after_or_equal:today',
            'cost_price' => 'required|numeric|min:0',
            'initial_quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string|max:255',
        ]);

        $batch = $medicine->batches()->create([
            'client_id' => $medicine->client_id,
            'batch_number' => $request->batch_number,
            'manufacture_date' => $request->manufacture_date,
            'expiry_date' => $request->expiry_date,
            'cost_price' => $request->cost_price,
            'initial_quantity' => $request->initial_quantity,
            'current_quantity' => $request->initial_quantity,
            'supplier' => $request->supplier,
        ]);

        $redirectRouteName = $superAdminUser ? 'superadmin.medicines.show' : 'admin.medicines.show';
        return redirect()->route($redirectRouteName, $medicine->id)
                         ->with('success', 'Batch added successfully!');
    }

    /**
     * Show the form for editing the specified batch.
     *
     * @param  \App\Models\Admin\Batch  $batch
     * @return \Illuminate\View\View
     */
    public function editBatch(Batch $batch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $batch->client_id || !$webUser->hasPermission('edit_batches'))) {
            abort(403, 'Unauthorized: You do not have permission to edit batches.');
        }
        return view($this->getViewPrefix() . 'batches.edit', compact('batch'));
    }

    /**
     * Update the specified batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Batch  $batch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBatch(Request $request, Batch $batch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $batch->client_id || !$webUser->hasPermission('edit_batches'))) {
            abort(403, 'Unauthorized: You do not have permission to update batches.');
        }

        $rules = [
            'batch_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('batches')->where(function ($query) use ($batch) {
                    return $query->where('medicine_id', $batch->medicine_id)
                                 ->where('client_id', $batch->client_id);
                })->ignore($batch->id),
            ],
            'manufacture_date' => 'nullable|date|before_or_equal:today',
            'expiry_date' => 'required|date|after_or_equal:today',
            'cost_price' => 'required|numeric|min:0',
            'initial_quantity' => 'required|integer|min:1',
            'current_quantity' => 'required|integer|min:0|lte:initial_quantity',
            'supplier' => 'nullable|string|max:255',
        ];

        $request->validate($rules);

        $batch->update($request->all());

        $redirectRouteName = $superAdminUser ? 'superadmin.medicines.show' : 'admin.medicines.show';
        return redirect()->route($redirectRouteName, $batch->medicine_id)
                         ->with('success', 'Batch updated successfully!');
    }

    /**
     * Remove the specified batch from storage.
     *
     * @param  \App\Models\Admin\Batch  $batch
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBatch(Batch $batch)
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        if (!$superAdminUser && (!$webUser || $webUser->client_id !== $batch->client_id || !$webUser->hasPermission('delete_batches'))) {
            abort(403, 'Unauthorized: You do not have permission to delete batches.');
        }

        $medicineId = $batch->medicine_id;
        $batch->delete();

        $redirectRouteName = $superAdminUser ? 'superadmin.medicines.show' : 'admin.medicines.show';
        return redirect()->route($redirectRouteName, $medicineId)
                         ->with('success', 'Batch deleted successfully!');
    }
}