@extends('layouts.dashboard')

@section('title', $branch->name . ' Branch Details - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Branch Details: {{ $branch->name }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Branch Name:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Client:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $client->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Address:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->address ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">City:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->city ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">State:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->state ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Zip Code:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->zip_code ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Phone:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Contact Person:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->contact_person ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status:</p>
                <p class="mt-1 text-lg text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $branch->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($branch->status) }}
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Created At:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $branch->created_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('branches.edit', [$client->id, $branch->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                Edit Branch
            </a>
            <a href="{{ route('branches.index', $client->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                Back to Branches List
            </a>
        </div>
    </div>
@endsection
