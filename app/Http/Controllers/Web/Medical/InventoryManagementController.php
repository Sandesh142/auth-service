<?php

namespace App\Http\Controllers\Web\Medical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Medicine; // Using the user's provided namespace
use App\Models\Admin\Batch;    // Using the user's provided namespace
use App\Models\Admin\Client;   // Using the user's provided namespace
use App\Models\User; // For web guard user
use App\Models\SuperAdmin\SuperAdmin; // For superadmin guard user
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class InventoryManagementController extends Controller
{
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
    // --- Medicine Management ---

    /**
     * Display a listing of medicines.
     * Accessible by Admin (their client's medicines) or SuperAdmin (all/filtered).
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function indexMedicines(Request $request)
    {
        // print_r("gsdfsdf"); die;
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        $query = Medicine::with('batches');
        $clients = collect(); // Initialize as empty collection

        if ($superAdminUser) {
            // SuperAdmin can filter by client
            if ($request->has('client_id') && $request->client_id != '') {
                $query->where('client_id', $request->client_id);
            }
            $clients = Client::all(); // For SuperAdmin to filter
        } elseif ($webUser) {
            // Admin/Staff can only see medicines belonging to their client
            $query->where('client_id', $webUser->client_id);
            $clients = Client::where('id', $webUser->client_id)->get(); // Only their client
        } else {
            // Should not be reached due to middleware, but as a fallback
            abort(403, 'Unauthorized: No authenticated user found.');
        }

        // Search functionality
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

        return view('admin-users.inventory.medicines.index', compact('medicines', 'clients'));
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

        // Authorization: SuperAdmin can create for any client. Admin needs 'create_medicines' for their client.
        if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_medicines'))) {
            abort(403, 'Unauthorized: You do not have permission to create medicines.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view('admin-users.inventory.medicines.create', compact('clients'));
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

    // Authorization check
    if (!$superAdminUser && (!$webUser || !$webUser->hasPermission('create_medicines'))) {
        abort(403, 'Unauthorized: You do not have permission to store medicines.');
    }

    // Base validation rules
    $rules = [
        'name'        => ['required', 'string', 'max:255'],
        'brand'       => ['nullable', 'string', 'max:255'],
        'category'    => ['nullable', 'string', 'max:255'],
        'strength'    => ['nullable', 'string', 'max:255'],
        'unit'        => ['nullable', 'string', 'max:50'],
        'barcode'     => ['nullable', 'string', 'max:255', 'unique:medicines,barcode'],
        'description' => ['nullable', 'string', 'max:1000'],
        'is_active'   => ['boolean'],
    ];

    // Determine client_id for validation
    $clientIdForValidation = null;

    if ($superAdminUser) {
        $rules['client_id'] = ['required', 'exists:clients,id'];
        $clientIdForValidation = $request->client_id;
    } elseif ($webUser) {
        $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
        $clientIdForValidation = $webUser->client_id;
    } else {
        abort(403, 'Unauthorized: No authenticated user to store medicines.');
    }

    // Add unique name rule per client
    $rules['name'][] = Rule::unique('medicines')->where(function ($query) use ($clientIdForValidation) {
        return $query->where('client_id', $clientIdForValidation);
    });

    // Validate the request
    $validatedData = $request->validate($rules);

    // Create the medicine
    $medicine = Medicine::create($validatedData);

    return redirect()
        ->route('medicines.show', $medicine->id)
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

        // Authorization: SuperAdmin can view any medicine. Admin needs 'view_medicines' for their client's medicine.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $medicine->client_id) {
            if (!$webUser->hasPermission('view_medicines')) {
                abort(403, 'Unauthorized: You do not have permission to view medicines.');
            }
        } else {
            abort(403, 'Unauthorized: You can only view medicines for your own client or you lack permission.');
        }

        $batches = $medicine->batches()->latest('expiry_date')->paginate(10); // Paginate batches

        return view('admin-users.inventory.medicines.show', compact('medicine', 'batches'));
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

        // Authorization: SuperAdmin can edit any medicine. Admin needs 'edit_medicines' for their client's medicine.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $medicine->client_id) {
            if (!$webUser->hasPermission('edit_medicines')) {
                abort(403, 'Unauthorized: You do not have permission to edit medicines.');
            }
        } else {
            abort(403, 'Unauthorized: You can only edit medicines for your own client or you lack permission.');
        }

        $clients = collect();
        if ($superAdminUser) {
            $clients = Client::all();
        } elseif ($webUser) {
            $clients = Client::where('id', $webUser->client_id)->get();
        }

        return view('admin-users.inventory.medicines.edit', compact('medicine', 'clients'));
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

        // Authorization: SuperAdmin can update any medicine. Admin needs 'edit_medicines' for their client's medicine.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $medicine->client_id) {
            if (!$webUser->hasPermission('edit_medicines')) {
                abort(403, 'Unauthorized: You do not have permission to update medicines.');
            }
        } else {
            abort(403, 'Unauthorized: You can only update medicines for your own client or you lack permission.');
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

        // Determine client_id for validation based on who is logged in
        $clientIdForValidation = null;
        if ($superAdminUser) {
            $rules['client_id'] = 'required|exists:clients,id';
            $clientIdForValidation = $request->client_id;
        } elseif ($webUser) {
            $rules['client_id'] = ['required', Rule::in([$webUser->client_id])];
            $clientIdForValidation = $webUser->client_id;
            // Ensure client_id is not changed by non-superadmin
            if ($request->client_id !== $medicine->client_id) {
                abort(403, 'Unauthorized: You cannot change the client for this medicine.');
            }
        } else {
            abort(403, 'Unauthorized: No authenticated user to update medicines.');
        }

        // Add unique rule for name per client, ignoring current medicine
        $rules['name'] = array_merge($rules['name'], [
            Rule::unique('medicines')->where(function ($query) use ($clientIdForValidation) {
                return $query->where('client_id', $clientIdForValidation);
            })->ignore($medicine->id),
        ]);

        $request->validate($rules);

        $medicine->update($request->all());

        return redirect()->route('medicines.show', $medicine->id)
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

        // Authorization: SuperAdmin can delete any medicine. Admin needs 'delete_medicines' for their client's medicine.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $medicine->client_id) {
            if (!$webUser->hasPermission('delete_medicines')) {
                abort(403, 'Unauthorized: You do not have permission to delete medicines.');
            }
        } else {
            abort(403, 'Unauthorized: You can only delete medicines for your own client or you lack permission.');
        }

        // Check if there are any batches associated before deleting medicine
        if ($medicine->batches()->exists()) {
            return back()->withErrors(['error' => 'Cannot delete medicine: It has associated batches. Delete batches first.']);
        }
        $medicine->delete();

        return redirect()->route('medicines.index')
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

        // Authorization: SuperAdmin can add batches for any medicine. Admin needs 'create_batches' for their client's medicine.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $medicine->client_id) {
            if (!$webUser->hasPermission('create_batches')) {
                abort(403, 'Unauthorized: You do not have permission to add batches.');
            }
        } else {
            abort(403, 'Unauthorized: You can only add batches for your own client\'s medicines or you lack permission.');
        }
        return view('admin-users.inventory.batches.create', compact('medicine'));
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

        // Authorization: SuperAdmin can store batches for any medicine. Admin needs 'create_batches' for their client's medicine.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $medicine->client_id) {
            if (!$webUser->hasPermission('create_batches')) {
                abort(403, 'Unauthorized: You do not have permission to store batches.');
            }
        } else {
            abort(403, 'Unauthorized: You can only add batches for your own client\'s medicines or you lack permission.');
        }

        $rules = [
            'batch_number' => [
                'required',
                'string',
                'max:255',
                // Unique per medicine and client
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
        ];

        $request->validate($rules);

        $batch = $medicine->batches()->create([
            'client_id' => $medicine->client_id, // Inherit client_id from medicine
            'batch_number' => $request->batch_number,
            'manufacture_date' => $request->manufacture_date,
            'expiry_date' => $request->expiry_date,
            'cost_price' => $request->cost_price,
            'initial_quantity' => $request->initial_quantity,
            'current_quantity' => $request->initial_quantity, // Initial stock is initial quantity
            'supplier' => $request->supplier,
        ]);

        return redirect()->route('medicines.show', $medicine->id)
                         ->with('success', 'Batch added successfully!');
    }

    /**
     * Show the form for editing the specified batch.
     *
     * @param  \App\Models\Admin\Batch  $batch
     * @return \Illuminate\View\View
     */
    public function editBatch(Medicine $medicine, Batch $batch) // Corrected method signature
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Optional: Add a check to ensure the batch belongs to the medicine
        if ($batch->medicine_id !== $medicine->id) {
            abort(404, 'Batch does not belong to the specified medicine.');
        }

        // Authorization: SuperAdmin can edit any batch. Admin needs 'edit_batches' for their client's batch.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $batch->client_id) {
            if (!$webUser->hasPermission('edit_batches')) {
                abort(403, 'Unauthorized: You do not have permission to edit batches.');
            }
        } else {
            abort(403, 'Unauthorized: You can only edit batches for your own client or you lack permission.');
        }

        // Ensure the medicine is also passed if needed in the view, though not strictly required by this method
        return view('admin-users.inventory.batches.edit', compact('batch', 'medicine'));
    }

    /**
     * Update the specified batch in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Batch  $batch
     * @return \Illuminate\Http\RedirectResponse
     */
     public function updateBatch(Request $request, Medicine $medicine, Batch $batch) // Corrected method signature
    {
        $webUser = Auth::guard('web')->user();
        $superAdminUser = Auth::guard('superadmin')->user();

        // Optional: Add a check to ensure the batch belongs to the medicine
        if ($batch->medicine_id !== $medicine->id) {
            abort(404, 'Batch does not belong to the specified medicine.');
        }

        // Authorization: SuperAdmin can update any batch. Admin needs 'edit_batches' for their client's batch.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $batch->client_id) {
            if (!$webUser->hasPermission('edit_batches')) {
                abort(403, 'Unauthorized: You do not have permission to update batches.');
            }
        } else {
            abort(403, 'Unauthorized: You can only update batches for your own client or you lack permission.');
        }

        $rules = [
            'batch_number' => [
                'required',
                'string',
                'max:255',
                // Unique per medicine and client, ignoring current batch
                Rule::unique('batches')->where(function ($query) use ($batch) {
                    return $query->where('medicine_id', $batch->medicine_id)
                                 ->where('client_id', $batch->client_id);
                })->ignore($batch->id),
            ],
            'manufacture_date' => 'nullable|date|before_or_equal:today',
            'expiry_date' => 'required|date|after_or_equal:today',
            'cost_price' => 'required|numeric|min:0',
            'initial_quantity' => 'required|integer|min:1',
            'current_quantity' => 'required|integer|min:0|lte:initial_quantity', // current cannot exceed initial
            'supplier' => 'nullable|string|max:255',
        ];

        $request->validate($rules);

        $batch->update($request->all());

        // Redirect back to the medicine show page, passing the medicine ID
        return redirect()->route('medicines.show', $batch->medicine_id)
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

        // Authorization: SuperAdmin can delete any batch. Admin needs 'delete_batches' for their client's batch.
        if ($superAdminUser) {
            // OK
        } elseif ($webUser && $webUser->client_id === $batch->client_id) {
            if (!$webUser->hasPermission('delete_batches')) {
                abort(403, 'Unauthorized: You do not have permission to delete batches.');
            }
        } else {
            abort(403, 'Unauthorized: You can only delete batches for your own client or you lack permission.');
        }

        $medicineId = $batch->medicine_id; // Store for redirect
        $batch->delete();

        return redirect()->route('medicines.show', $medicineId)
                         ->with('success', 'Batch deleted successfully!');
    }
}
