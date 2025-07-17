@extends('layouts.dashboard')

@section('title', $role->name . ' Details - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Role Details: {{ $role->name }}</h1>

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

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Role Name:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $role->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Client:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $role->client->name ?? 'System Role' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Guard Name:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $role->guard_name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status:</p>
                <p class="mt-1 text-lg text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($role->status === 'active') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($role->status) }}
                    </span>
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Description:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $role->description ?? 'N/A' }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Assigned Permissions:</p>
                <div class="mt-1 flex flex-wrap gap-2">
                    @forelse ($role->permissions as $permission)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $permission->name }}
                        </span>
                    @empty
                        <p class="text-gray-600">No permissions assigned.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Created At:</p>
                <p class="mt-1 text-lg text-gray-900">
                    {{ $role->created_at ? $role->created_at->format('M d, Y H:i A') : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Last Updated At:</p>
                <p class="mt-1 text-lg text-gray-900">
                    {{ $role->updated_at ? $role->updated_at->format('M d, Y H:i A') : 'N/A' }}
                </p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            @if (Auth::guard('superadmin')->check() || (Auth::guard('web')->check() && Auth::user()->hasPermission('edit_roles')))
                <a href="{{ route('roles.edit', $role->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                    Edit Role
                </a>
            @endif
            <a href="{{ route('roles.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                Back to List
            </a>
        </div>
    </div>
@endsection
