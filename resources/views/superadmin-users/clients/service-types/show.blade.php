@extends('layouts.dashboard')

@section('title', $serviceType->name . ' Service Type Details - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Service Type Details: {{ $serviceType->name }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Service Name:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $serviceType->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Client:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $client->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Default Price:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $serviceType->default_price ? 'INR ' . number_format($serviceType->default_price, 2) : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status:</p>
                <p class="mt-1 text-lg text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $serviceType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $serviceType->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Description:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $serviceType->description ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Created At:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $serviceType->created_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('superadmin.service-types.edit', [$client->id, $serviceType->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                Edit Service Type
            </a>
            <a href="{{ route('superadmin.service-types.index', $client->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                Back to Service Types List
            </a>
        </div>
    </div>
@endsection
