@extends('layouts.dashboard')

@section('title', 'Revenue Entry Details - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Revenue Entry Details</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Client:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->client->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Service Type:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->serviceType->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Branch:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->branch->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Recorded By:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Amount:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->currency }} {{ number_format($revenueEntry->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Entry Date:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->entry_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status:</p>
                <p class="mt-1 text-lg text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $revenueEntry->status === 'completed' ? 'bg-green-100 text-green-800' : ($revenueEntry->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($revenueEntry->status) }}
                    </span>
                </p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Description:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->description ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Created At:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->created_at->format('M d, Y H:i A') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Last Updated At:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $revenueEntry->updated_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('revenue.edit', $revenueEntry->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                Edit Entry
            </a>
            <a href="{{ route('revenue.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                Back to List
            </a>
        </div>
    </div>
@endsection
