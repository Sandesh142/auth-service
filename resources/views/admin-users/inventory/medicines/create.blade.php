@extends('layouts.dashboard')

@section('title', 'Add New Medicine - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Add New Medicine</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('medicines.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if (Auth::user()->hasSystemRole('superadmin'))
                    <div>
                        <label for="client_id" class="block text-gray-700 text-sm font-medium mb-2">Assign to Client</label>
                        <select id="client_id" name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('client_id') border-red-500 @enderror" required>
                            <option value="">Select Client</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <input type="hidden" name="client_id" value="{{ Auth::user()->client_id }}">
                @endif

                <div>
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Medicine Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand" class="block text-gray-700 text-sm font-medium mb-2">Brand (Optional)</label>
                    <input type="text" id="brand" name="brand" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('brand') border-red-500 @enderror" value="{{ old('brand') }}">
                    @error('brand')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-gray-700 text-sm font-medium mb-2">Category (Optional)</label>
                    <input type="text" id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('category') border-red-500 @enderror" value="{{ old('category') }}">
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="strength" class="block text-gray-700 text-sm font-medium mb-2">Strength (Optional)</label>
                    <input type="text" id="strength" name="strength" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('strength') border-red-500 @enderror" value="{{ old('strength') }}">
                    @error('strength')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit" class="block text-gray-700 text-sm font-medium mb-2">Unit (e.g., Tablet, ml)</label>
                    <input type="text" id="unit" name="unit" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('unit') border-red-500 @enderror" value="{{ old('unit') }}">
                    @error('unit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="barcode" class="block text-gray-700 text-sm font-medium mb-2">Barcode (Optional, Unique)</label>
                    <input type="text" id="barcode" name="barcode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('barcode') border-red-500 @enderror" value="{{ old('barcode') }}">
                    @error('barcode')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-gray-700 text-sm font-medium mb-2">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-gray-700 text-sm font-medium mb-2">Status</label>
                    <select id="is_active" name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('is_active') border-red-500 @enderror" required>
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('medicines.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Save Medicine
                </button>
            </div>
        </form>
    </div>
@endsection