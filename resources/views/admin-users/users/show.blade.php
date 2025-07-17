@extends('layouts.dashboard')

@section('title', $userToManage->name . ' Details - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">User Details: {{ $userToManage->name }}</h1>

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
                <p class="text-sm font-medium text-gray-500">Name:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $userToManage->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Email:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $userToManage->email }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Client:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $userToManage->client->name ?? 'N/A (System User)' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">System Role:</p>
                <p class="mt-1 text-lg text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($userToManage->role === 'superadmin') bg-purple-100 text-purple-800
                        @elseif($userToManage->role === 'admin') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($userToManage->role) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Clinic Role(s):</p>
                <p class="mt-1 text-lg text-gray-900">
                    @forelse ($userToManage->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                            {{ $role->name }}
                        </span>
                    @empty
                        N/A
                    @endforelse
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Created At:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $userToManage->created_at->format('M d, Y H:i A') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Last Updated At:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $userToManage->updated_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            @if (Auth::user()->hasSystemRole('superadmin') || (Auth::user()->hasSystemRole('admin') && $userToManage->client_id === Auth::user()->client_id && !$userToManage->hasSystemRole('admin') && !$userToManage->hasSystemRole('superadmin')))
                <a href="{{ route('users.edit', $userToManage->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                    Edit User
                </a>
            @endif
            <a href="{{ route('users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                Back to List
            </a>
        </div>
    </div>
@endsection
