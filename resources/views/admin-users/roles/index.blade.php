@extends('layouts.dashboard')

@section('title', 'Manage Clinic Roles - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Clinic Role Management</h1>

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
        <p class="text-gray-700">Manage roles and their permissions for your clinic.</p>
        @if (Auth::guard('superadmin')->check() || (Auth::guard('web')->check() && Auth::user()->hasPermission('create_roles')))
            <a href="{{ route('roles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                Add New Role
            </a>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6 p-4">
        <form action="{{ route('roles.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            @if (Auth::guard('superadmin')->check())
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Filter by Client</label>
                    <select id="client_id" name="client_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Clients (SuperAdmin)</option>
                        <option value="null" {{ request('client_id') === 'null' ? 'selected' : '' }}>System Roles (No Client)</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Role Name</label>
                <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Search by name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
                        Role Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Client
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Permissions
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $role->client->name ?? 'System Role' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($role->description, 50) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($role->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($role->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @forelse ($role->permissions->take(3) as $permission)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                    {{ $permission->name }}
                                </span>
                            @empty
                                No Permissions
                            @endforelse
                            @if ($role->permissions->count() > 3)
                                <span class="text-xs text-gray-500">...</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('roles.show', $role->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            @if (Auth::guard('superadmin')->check() || (Auth::guard('web')->check() && Auth::user()->hasPermission('edit_roles')))
                                <a href="{{ route('roles.edit', $role->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                            @endif
                            @if (Auth::guard('superadmin')->check() || (Auth::guard('web')->check() && Auth::user()->hasPermission('delete_roles')))
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role? This will also remove it from any assigned users.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No roles found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $roles->links() }}
        </div>
    </div>
@endsection
