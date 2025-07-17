@extends('layouts.dashboard')

@section('title', 'Edit User - ' . $userToManage->name . ' - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-6">Edit User: {{ $userToManage->name }}</h1>

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
        {{-- Form action, uses superadmin.users.update --}}
        <form action="{{ route('superadmin.users.update', $userToManage->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror" value="{{ old('name', $userToManage->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror" value="{{ old('email', $userToManage->email) }}" required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">New Password (Leave blank to keep current)</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Conditional fields based on SuperAdmin or Clinic Admin --}}
                @if (Auth::guard('superadmin')->check())
                    <div>
                        <label for="client_id" class="block text-gray-700 text-sm font-medium mb-2">Assign to Client</label>
                        <select id="client_id" name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('client_id') border-red-500 @enderror">
                            <option value="">No Client (System User)</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $userToManage->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="system_role" class="block text-gray-700 text-sm font-medium mb-2">System Role</label>
                        <select id="system_role" name="system_role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('system_role') border-red-500 @enderror" required>
                            <option value="">Select System Role</option>
                            <option value="superadmin" {{ old('system_role', $userToManage->role) == 'superadmin' ? 'selected' : '' }}>SuperAdmin</option>
                            <option value="admin" {{ old('system_role', $userToManage->role) == 'admin' ? 'selected' : '' }}>Admin (Clinic Owner)</option>
                            <option value="staff" {{ old('system_role', $userToManage->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                        @error('system_role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @else {{-- For Clinic Admin (Auth::guard('web')->check()) --}}
                    <input type="hidden" name="client_id" value="{{ Auth::user()->client_id }}">
                    <input type="hidden" name="system_role" value="staff"> {{-- Clinic Admin can only manage staff --}}
                    <div class="col-span-2 text-sm text-gray-600">
                        This user is a 'Staff' member for your clinic ({{ Auth::user()->client->name ?? 'N/A' }}). System role cannot be changed by Clinic Admin.
                    </div>
                @endif

                <div>
                    <label for="clinic_role_id" class="block text-gray-700 text-sm font-medium mb-2">Clinic Role (Optional)</label>
                    <select id="clinic_role_id" name="clinic_role_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('clinic_role_id') border-red-500 @enderror">
                        <option value="">No Specific Clinic Role</option>
                        @foreach ($clinicRoles as $role) {{-- Use $clinicRoles variable --}}
                            <option value="{{ $role->id }}" {{ old('clinic_role_id', $currentClinicRoleId) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('clinic_role_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="is_active" class="block text-gray-700 text-sm font-medium mb-2">Status</label>
                    <select id="is_active" name="is_active" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 @error('is_active') border-red-500 @enderror" required>
                        <option value="1" {{ old('is_active', $userToManage->is_active) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $userToManage->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                {{-- Cancel button, uses superadmin.users.show --}}
                <a href="{{ route('superadmin.users.show', $userToManage->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-300 font-semibold">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold">
                    Update User
                </button>
            </div>
        </form>
    </div>
@endsection
