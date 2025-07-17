@extends('layouts.dashboard')

@section('title', 'Edit Batch - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Edit Batch for {{ $batch->medicine->name }}</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('batches.update', [$batch->medicine->id, $batch->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="batch_number" class="block text-gray-700 text-sm font-medium mb-2">Batch Number</label>
                    <input type="text" id="batch_number" name="batch_number" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('batch_number') border-red-500 @enderror" value="{{ old('batch_number', $batch->batch_number) }}" required>
                    @error('batch_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="manufacture_date" class="block text-gray-700 text-sm font-medium mb-2">Manufacture Date (Optional)</label>
                    <input type="date" id="manufacture_date" name="manufacture_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('manufacture_date') border-red-500 @enderror" value="{{ old('manufacture_date', $batch->manufacture_date ? $batch->manufacture_date->format('Y-m-d') : '') }}">
                    @error('manufacture_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="expiry_date" class="block text-gray-700 text-sm font-medium mb-2">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('expiry_date') border-red-500 @enderror" value="{{ old('expiry_date', $batch->expiry_date->format('Y-m-d')) }}" required>
                    @error('expiry_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cost_price" class="block text-gray-700 text-sm font-medium mb-2">Cost Price (per unit)</label>
                    <input type="number" step="0.01" id="cost_price" name="cost_price" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('cost_price') border-red-500 @enderror" value="{{ old('cost_price', $batch->cost_price) }}" required min="0">
                    @error('cost_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="initial_quantity" class="block text-gray-700 text-sm font-medium mb-2">Initial Quantity</label>
                    <input type="number" id="initial_quantity" name="initial_quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('initial_quantity') border-red-500 @enderror" value="{{ old('initial_quantity', $batch->initial_quantity) }}" required min="1">
                    @error('initial_quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="current_quantity" class="block text-gray-700 text-sm font-medium mb-2">Current Quantity</label>
                    <input type="number" id="current_quantity" name="current_quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('current_quantity') border-red-500 @enderror" value="{{ old('current_quantity', $batch->current_quantity) }}" required min="0">
                    @error('current_quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supplier" class="block text-gray-700 text-sm font-medium mb-2">Supplier (Optional)</label>
                    <input type="text" id="supplier" name="supplier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('supplier') border-red-500 @enderror" value="{{ old('supplier', $batch->supplier) }}">
                    @error('supplier')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('medicines.show', $batch->medicine_id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Update Batch
                </button>
            </div>
        </form>
    </div>
@endsection