@extends('layouts.dashboard')

@section('title', 'Edit Role - ' . $role->name . ' - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Edit Role: {{ $role->name }}</h1>

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
        {{-- Form action, always uses superadmin.roles.update --}}
        <form action="{{ route('superadmin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Client ID selection is only for SuperAdmin, and this view is already for SuperAdmin --}}
                <div>
                    <label for="client_id" class="block text-gray-700 text-sm font-medium mb-2">Assign to Client (Optional for System Roles)</label>
                    <select id="client_id" name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('client_id') border-red-500 @enderror">
                        <option value="">System Role (No Client)</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $role->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Role Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="guard_name" class="block text-gray-700 text-sm font-medium mb-2">Guard Name</label>
                    <input type="text" id="guard_name" name="guard_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('guard_name') border-red-500 @enderror" value="{{ old('guard_name', $role->guard_name) }}" required>
                    @error('guard_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-gray-700 text-sm font-medium mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" required>
                        <option value="active" {{ old('status', $role->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $role->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="permissions" class="block text-gray-700 text-sm font-medium mb-2">Assign Permissions</label>
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="select_all_permissions" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="select_all_permissions" class="ml-2 block text-base font-semibold text-gray-900">
                                Select All Permissions
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($permissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->id }}"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 permission-checkbox"
                                    {{ in_array($permission->id, old('permissions', $currentPermissionIds ?? [])) ? 'checked' : '' }}>
                                <label for="permission_{{ $permission->id }}" class="ml-2 block text-sm text-gray-900">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                {{-- Cancel button, always uses superadmin.roles.show --}}
                <a href="{{ route('superadmin.roles.show', $role->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Update Role
                </button>
            </div>
        </form>
    </div>

    {{-- Moved script block here, directly after the form --}}
    <script>
        // This script will run as soon as it's parsed, after the HTML for the form.
        // It does not need DOMContentLoaded if placed at the end of the body or after the elements.
        // However, keeping DOMContentLoaded is safer if other scripts might re-render parts of the DOM.

        const selectAllCheckbox = document.getElementById('select_all_permissions');

        // Function to dynamically fetch checkboxes each time (ensures fresh DOM query)
        function getPermissionCheckboxes() {
            return document.querySelectorAll('.permission-checkbox');
        }

        // Function to update "Select All" checkbox state
        function updateSelectAllCheckbox() {
            const permissionCheckboxes = getPermissionCheckboxes();
            const totalCheckboxes = permissionCheckboxes.length;
            const checkedCheckboxes = document.querySelectorAll('.permission-checkbox:checked').length;
            // Only check "Select All" if there are actual checkboxes and all are checked
            if (selectAllCheckbox) { // Check if selectAllCheckbox is not null before accessing .checked
                selectAllCheckbox.checked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
            }
            console.log('updateSelectAllCheckbox called. Total:', totalCheckboxes, 'Checked:', checkedCheckboxes, 'Select All checked:', selectAllCheckbox ? selectAllCheckbox.checked : 'N/A (checkbox not found)');
        }

        // --- DEBUGGING LOGS ---
        console.log('Script loaded. (Directly in Blade)');
        console.log('Attempting to find selectAllCheckbox...');
        console.log('Found selectAllCheckbox:', selectAllCheckbox); // This will tell us if it's null or the element
        // --- END DEBUGGING LOGS ---

        // Event listener for "Select All" checkbox
        if (selectAllCheckbox) {
            console.log('Attaching change event listener to selectAllCheckbox.');
            selectAllCheckbox.addEventListener('change', function () {
                // alert("fsdfdsf"); // Re-enabled alert for direct confirmation
                console.log('Select All checkbox changed. New state:', this.checked);
                const permissionCheckboxes = getPermissionCheckboxes(); // Re-fetch to be safe
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                // After changing all, re-run updateSelectAllCheckbox to ensure consistency
                updateSelectAllCheckbox();
            });
        } else {
            console.error('Error: "Select All" checkbox (ID: select_all_permissions) not found!');
        }

        // Event listener for individual permission checkboxes
        const initialPermissionCheckboxes = getPermissionCheckboxes(); // Get initial list
        if (initialPermissionCheckboxes.length > 0) {
            console.log('Attaching change event listeners to individual permission checkboxes.');
            initialPermissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllCheckbox);
            });
        } else {
            console.warn('No individual permission checkboxes found with class "permission-checkbox".');
        }

        // Initial check when the page loads
        updateSelectAllCheckbox();
    </script>
@endsection
