@extends('layouts.dashboard')

@section('title', 'Add New Revenue Entry - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Add New Revenue Entry</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('revenue.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if (Auth::user()->hasSystemRole('superadmin'))
                    <div>
                        <label for="client_id" class="block text-gray-700 text-sm font-medium mb-2">Client</label>
                        <select id="client_id" name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('client_id') border-red-500 @enderror" required>
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="client_id" id="client_id" value="{{ Auth::user()->client_id }}">
                @endif

                <div>
                    <label for="client_service_type_id" class="block text-gray-700 text-sm font-medium mb-2">Service Type</label>
                    <select id="client_service_type_id" name="client_service_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('client_service_type_id') border-red-500 @enderror" required>
                        <option value="">Select Service Type</option>
                        {{-- Options will be loaded by JavaScript or directly if not SuperAdmin --}}
                        @if (!Auth::user()->hasSystemRole('superadmin'))
                            @foreach ($serviceTypes as $serviceType)
                                <option value="{{ $serviceType->id }}" {{ old('client_service_type_id') == $serviceType->id ? 'selected' : '' }}>{{ $serviceType->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('client_service_type_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="branch_id" class="block text-gray-700 text-sm font-medium mb-2">Branch (Optional)</label>
                    <select id="branch_id" name="branch_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('branch_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        {{-- Options will be loaded by JavaScript or directly if not SuperAdmin --}}
                        @if (!Auth::user()->hasSystemRole('superadmin'))
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('branch_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="amount" class="block text-gray-700 text-sm font-medium mb-2">Amount</label>
                    <input type="number" step="0.01" id="amount" name="amount" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror" value="{{ old('amount') }}" required min="0.01">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="currency" class="block text-gray-700 text-sm font-medium mb-2">Currency</label>
                    <input type="text" id="currency" name="currency" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('currency') border-red-500 @enderror" value="{{ old('currency', 'INR') }}" required maxlength="3">
                    @error('currency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="entry_date" class="block text-gray-700 text-sm font-medium mb-2">Entry Date</label>
                    <input type="date" id="entry_date" name="entry_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('entry_date') border-red-500 @enderror" value="{{ old('entry_date', now()->toDateString()) }}" required>
                    @error('entry_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-gray-700 text-sm font-medium mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" required>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('revenue.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Create Revenue Entry
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clientIdSelect = document.getElementById('client_id');
            const branchIdSelect = document.getElementById('branch_id');
            const serviceTypeIdSelect = document.getElementById('client_service_type_id');

            function updateDependentDropdowns(selectedClientId, oldBranchId = null, oldServiceTypeId = null) {
                // Clear current options, except for the default "Select..."
                branchIdSelect.innerHTML = '<option value="">Select Branch</option>';
                serviceTypeIdSelect.innerHTML = '<option value="">Select Service Type</option>';

                if (!selectedClientId) {
                    return;
                }

                // Fetch branches for the selected client
                fetch(`/api/branches-by-client/${selectedClientId}`) // You'll need to create this API route
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(branch => {
                            const option = document.createElement('option');
                            option.value = branch.id;
                            option.textContent = branch.name;
                            if (oldBranchId && branch.id == oldBranchId) {
                                option.selected = true;
                            }
                            branchIdSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching branches:', error));

                // Fetch service types for the selected client
                fetch(`/api/service-types-by-client/${selectedClientId}`) // You'll need to create this API route
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(serviceType => {
                            const option = document.createElement('option');
                            option.value = serviceType.id;
                            option.textContent = serviceType.name;
                            if (oldServiceTypeId && serviceType.id == oldServiceTypeId) {
                                option.selected = true;
                            }
                            serviceTypeIdSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching service types:', error));
            }

            // Initial call if client_id is already set (e.g., for Admin user, or after validation error for SuperAdmin)
            const initialClientId = clientIdSelect.value;
            if (initialClientId) {
                updateDependentDropdowns(initialClientId, "{{ old('branch_id') }}", "{{ old('client_service_type_id') }}");
            }

            // Event listener for client change (only for SuperAdmin)
            if (clientIdSelect && "{{ Auth::user()->hasSystemRole('superadmin') }}") {
                clientIdSelect.addEventListener('change', function() {
                    updateDependentDropdowns(this.value);
                });
            }
        });
    </script>
@endsection
