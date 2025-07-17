@extends('layouts.dashboard')

@section('title', 'Manage Users - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">User Management</h1>

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
        <p class="text-gray-700">Manage user accounts for your system or clinic.</p>
        @php
            $webUser = Auth::guard('web')->user();
            $superAdminUser = Auth::guard('superadmin')->user();
        @endphp
        {{-- Check if SuperAdmin is logged in OR if a Web User (Admin) is logged in and has 'create_users' permission --}}
        @if ($superAdminUser || ($webUser && $webUser->hasPermission('create_users')))
            <a href="{{ route('superadmin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                Add New User
            </a>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 p-4">
        {{-- Form action, uses superadmin.users.index --}}
        <form action="{{ route('superadmin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
            {{-- Search input --}}
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Name or Email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            {{-- Client filter (only for SuperAdmin) --}}
            @if ($superAdminUser)
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Filter by Client</label>
                    <select id="client_id" name="client_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Clients</option>
                        <option value="null" {{ request('client_id') === 'null' ? 'selected' : '' }}>System Users (No Client)</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- System Role filter --}}
            <div>
                <label for="system_role" class="block text-sm font-medium text-gray-700">Filter by System Role</label>
                <select id="system_role" name="system_role" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All System Roles</option>
                    @if ($superAdminUser)
                        <option value="superadmin" {{ request('system_role') == 'superadmin' ? 'selected' : '' }}>SuperAdmin</option>
                        <option value="admin" {{ request('system_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ request('system_role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    @else {{-- For Clinic Admin, only 'Staff' is relevant --}}
                        <option value="staff" {{ request('system_role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    @endif
                </select>
            </div>

            {{-- Clinic Role filter --}}
            <div>
                <label for="clinic_role_id" class="block text-sm font-medium text-gray-700">Filter by Clinic Role</label>
                <select id="clinic_role_id" name="clinic_role_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Clinic Roles</option>
                    @foreach ($clinicRoles as $role)
                        <option value="{{ $role->id }}" {{ request('clinic_role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

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
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        System Role
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Clinic Role
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
                @forelse ($usersToManage as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $user->client->name ?? 'N/A (System User)' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ ucfirst($user->role) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{-- Display the single clinic role --}}
                            @if ($user->clinicRole)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $user->clinicRole->name }}
                                </span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- Action links --}}
                            <a href="{{ route('superadmin.users.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            @if ($superAdminUser || ($webUser && $webUser->hasPermission('edit_users')))
                                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            @endif
                            @if ($superAdminUser || ($webUser && $webUser->hasPermission('delete_users')))
                                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $usersToManage->links() }}
        </div>
    </div>
@endsection
