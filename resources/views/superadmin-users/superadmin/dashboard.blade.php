@extends('layouts.dashboard') 

@section('title', 'SuperAdmin Dashboard - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-8">Welcome to Your SuperAdmin Dashboard!</h1>
    <p class="text-lg text-gray-700 mb-6">
        You are logged in as a SuperAdmin: <span class="font-semibold">{{ Auth::guard('superadmin')->user()->name ?? 'SuperAdmin' }}</span>.
        This is your central control panel for PharmaPulse.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-indigo-700 mb-3">Client Management</h3>
            <p class="text-gray-600">View, add, edit, and disable client accounts.</p>
            <a href="#" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-semibold">Go to Clients &rarr;</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-indigo-700 mb-3">Subscription Management</h3>
            <p class="text-gray-600">Manage subscription plans, billing, and quotas.</p>
            <a href="#" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-semibold">Manage Subscriptions &rarr;</a>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-indigo-700 mb-3">System Logs & Audit</h3>
            <p class="text-gray-600">Access comprehensive system activity and audit trails.</p>
            <a href="#" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 font-semibold">View Logs &rarr;</a>
        </div>
    </div>

    <form method="POST" action="{{ route('superadmin.logout') }}" class="mt-10">
        @csrf
        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300 font-semibold">
            Logout SuperAdmin
        </button>
    </form>
@endsection