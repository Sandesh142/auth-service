@extends('layouts.dashboard')

@section('title', 'Revenue Management - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Revenue Management</h1>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-700">View and manage all revenue entries across all clients.</p>
        {{-- SuperAdmin always uses superadmin.revenue.create route --}}
        <a href="{{ route('superadmin.revenue.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
            Add New Revenue Entry
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 p-4">
        {{-- Form action always points to superadmin.revenue.index --}}
        <form action="{{ route('superadmin.revenue.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            {{-- Filter by Client (Always visible for SuperAdmin) --}}
            <div>
                <label for="client_id" class="block text-sm font-medium text-gray-700">Filter by Client</label>
                <select id="client_id" name="client_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Clients</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Branch (Populated dynamically based on client selection) --}}
            <div>
                <label for="branch_id" class="block text-sm font-medium text-gray-700">Filter by Branch</label>
                <select id="branch_id" name="branch_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Branches</option>
                    {{-- Options will be loaded by JavaScript --}}
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Service Type (Populated dynamically based on client selection) --}}
            <div>
                <label for="service_type_id" class="block text-sm font-medium text-gray-700">Filter by Service Type</label>
                <select id="service_type_id" name="service_type_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Service Types</option>
                    {{-- Options will be loaded by JavaScript --}}
                    @foreach ($serviceTypes as $serviceType)
                        <option value="{{ $serviceType->id }}" {{ request('service_type_id') == $serviceType->id ? 'selected' : '' }}>{{ $serviceType->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date Filters --}}
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Apply Filters Button --}}
            <div>
                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Service Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Branch
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Amount
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($revenueEntries as $entry)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $entry->client->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $entry->serviceType->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $entry->branch->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $entry->currency ?? '₹' }} {{ number_format($entry->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $entry->entry_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $entry->status === 'completed' ? 'bg-green-100 text-green-800' : ($entry->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- All action links for SuperAdmin use superadmin.revenue.* routes --}}
                            <a href="{{ route('superadmin.revenue.show', $entry->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('superadmin.revenue.edit', $entry->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            <form action="{{ route('superadmin.revenue.destroy', $entry->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this revenue entry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No revenue entries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $revenueEntries->links() }}
        </div>
    </div>

    <script>
        // JavaScript to dynamically load branches and service types based on client selection
        document.addEventListener('DOMContentLoaded', function() {
            const clientIdSelect = document.getElementById('client_id');
            const branchIdSelect = document.getElementById('branch_id');
            const serviceTypeIdSelect = document.getElementById('service_type_id');

            // Function to populate a dropdown
            function populateDropdown(selectElement, data, selectedValue) {
                // Clear current options, but keep the "All" option
                selectElement.innerHTML = `<option value="">All ${selectElement.id.replace('_id', '').replace(/([A-Z])/g, ' $1').trim()}s</option>`;
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    if (item.id == selectedValue) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                });
            }

            // Function to fetch and update dropdowns
            function updateDropdowns(selectedClientId) {
                // Reset dropdowns
                populateDropdown(branchIdSelect, [], ''); // Clear branches
                populateDropdown(serviceTypeIdSelect, [], ''); // Clear service types

                if (!selectedClientId) {
                    return; // No client selected, nothing to fetch
                }

                // API routes for SuperAdmin
                const apiPrefix = '/api/superadmin';

                // Fetch branches for selected client
                fetch(`${apiPrefix}/clients/${selectedClientId}/branches`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        populateDropdown(branchIdSelect, data, "{{ request('branch_id') }}");
                    })
                    .catch(error => console.error('Error fetching branches:', error));

                // Fetch service types for selected client
                fetch(`${apiPrefix}/clients/${selectedClientId}/service-types`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        populateDropdown(serviceTypeIdSelect, data, "{{ request('service_type_id') }}");
                    })
                    .catch(error => console.error('Error fetching service types:', error));
            }

            // Initial load if a client is already selected (e.g., after form submission or direct URL with filters)
            if (clientIdSelect && clientIdSelect.value) {
                updateDropdowns(clientIdSelect.value);
            }

            // Event listener for client change
            if (clientIdSelect) {
                clientIdSelect.addEventListener('change', function() {
                    updateDropdowns(this.value);
                });
            }
        });
    </script>
@endsection
