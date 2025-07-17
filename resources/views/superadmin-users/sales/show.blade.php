@extends('layouts.dashboard')

@section('title', 'Sale Details - ' . $sale->invoice_number . ' - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Sale Details: {{ $sale->invoice_number }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-sm font-medium text-gray-500">Invoice Number:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->invoice_number }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Sale Date:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->created_at->format('M d, Y H:i A') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Client:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->client->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Sold By:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Branch:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->branch->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Payment Method:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->payment_method }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-sm font-medium text-gray-500">Notes:</p>
                <p class="mt-1 text-lg text-gray-900">{{ $sale->notes ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Status:</p>
                <p class="mt-1 text-lg text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : ($sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($sale->status) }}
                    </span>
                </p>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-indigo-800 mb-4">Items Sold</h2>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batch No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Price</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($sale->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->medicine->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->batch->batch_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">INR {{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">INR {{ number_format($item->discount_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">INR {{ number_format($item->tax_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">INR {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No items in this sale.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <div class="w-full md:w-1/2 lg:w-1/3 bg-gray-50 p-4 rounded-lg shadow-inner">
                <div class="flex justify-between mb-2">
                    <span class="font-medium text-gray-700">Sub Total:</span>
                    <span class="font-bold text-gray-900">INR {{ number_format($sale->sub_total, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium text-gray-700">Discount:</span>
                    <span class="font-bold text-gray-900">INR {{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium text-gray-700">Tax:</span>
                    <span class="font-bold text-gray-900">INR {{ number_format($sale->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between border-t-2 border-gray-200 pt-2 mb-4">
                    <span class="font-bold text-lg text-indigo-800">Grand Total:</span>
                    <span class="font-extrabold text-lg text-indigo-800">INR {{ number_format($sale->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium text-gray-700">Amount Paid:</span>
                    <span class="font-bold text-gray-900">INR {{ number_format($sale->amount_paid, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-medium text-gray-700">Change Due:</span>
                    <span class="font-bold text-gray-900">INR {{ number_format($sale->change_due, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('sales.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                Back to Sales List
            </a>
            <a href="{{ route('sales.edit', $sale->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300 font-semibold">
                Edit Sale
            </a>
            {{-- You might add a "Print Invoice" button here --}}
        </div>
    </div>
@endsection