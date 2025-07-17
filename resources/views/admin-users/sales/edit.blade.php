@extends('layouts.dashboard')

@section('title', 'Edit Sale - ' . $sale->invoice_number . ' - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Edit Sale: {{ $sale->invoice_number }}</h1>

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
        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">Invoice Number:</p>
                    <p class="mt-1 text-lg text-gray-900">{{ $sale->invoice_number }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Amount:</p>
                    <p class="mt-1 text-lg text-gray-900">INR {{ number_format($sale->total_amount, 2) }}</p>
                </div>
                <div>
                    <label for="payment_method" class="block text-gray-700 text-sm font-medium mb-2">Payment Method</label>
                    <select id="payment_method" name="payment_method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('payment_method') border-red-500 @enderror" required>
                        <option value="Cash" {{ old('payment_method', $sale->payment_method) == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Card" {{ old('payment_method', $sale->payment_method) == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="UPI" {{ old('payment_method', $sale->payment_method) == 'UPI' ? 'selected' : '' }}>UPI</option>
                        <option value="Other" {{ old('payment_method', $sale->payment_method) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="status" class="block text-gray-700 text-sm font-medium mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" required>
                        <option value="completed" {{ old('status', $sale->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ old('status', $sale->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ old('status', $sale->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-gray-700 text-sm font-medium mb-2">Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes', $sale->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('sales.show', $sale->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Update Sale
                </button>
            </div>
        </form>
    </div>
@endsection