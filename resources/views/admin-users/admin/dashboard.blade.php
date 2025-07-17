@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - PharmaPulse')

@section('content')
    <h1 class="text-3xl font-bold text-indigo-800 mb-8">Welcome to Your PharmaPulse Dashboard!</h1>
    <p class="text-lg text-gray-700 mb-6">
        You are logged in as <span class="font-semibold">{{ Auth::guard('web')->user()->name ?? 'Admin' }}</span>.
        Here's an overview of your Pharma Revenue Management System.
    </p>

    <!-- Overall Revenue Summary -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Revenue Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-600">Total Revenue (Last 30 Days)</p>
                <p class="text-2xl font-bold text-indigo-800">₹ 1,250,000</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-600">Invoices Paid</p>
                <p class="text-2xl font-bold text-green-700">₹ 1,100,000</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-600">Outstanding Invoices</p>
                <p class="text-2xl font-bold text-red-700">₹ 150,000</p>
            </div>
        </div>
        <div class="mt-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Revenue Trends (Last 6 Months)</h3>
            {{-- Placeholder for a chart --}}
            <div class="bg-gray-100 p-4 rounded-lg h-48 flex items-center justify-center text-gray-500">
                [Placeholder for Revenue Trend Chart - e.g., using Chart.js]
            </div>
        </div>
    </div>

    <!-- Core Features Section -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Core System Features</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.5 3-3 3s-3-1.343-3-3V4m5 10v6m-3-6h6m-3-6a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">User Authentication</h3>
                    <p class="text-gray-600 text-sm">Role-based access for Admins, Staff, SuperAdmins.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Revenue Tracking</h3>
                    <p class="text-gray-600 text-sm">Manual or automatic entry by service type.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Reports & Analytics</h3>
                    <p class="text-gray-600 text-sm">Revenue breakdowns by service, date, location.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Invoice & Export</h3>
                    <p class="text-gray-600 text-sm">Auto-generate PDF, send by email.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V10m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Alerts & Notifications</h3>
                    <p class="text-gray-600 text-sm">Low sales, late reports, etc.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Service Management</h3>
                    <p class="text-gray-600 text-sm">Pharmacy, Lab, Doctor, Online Sales.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Microservices Breakdown Section -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Microservices Architecture</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Auth Service</h3>
                    <p class="text-gray-600 text-sm">User login, registration, roles (Laravel Sanctum).</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Revenue Service</h3>
                    <p class="text-gray-600 text-sm">Core service for all revenue tracking.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Invoice Service</h3>
                    <p class="text-gray-600 text-sm">Generates PDF invoices & exports.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zM21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Analytics Service</h3>
                    <p class="text-gray-600 text-sm">Provides reports, charts, forecasts.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V10m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Notification Service</h3>
                    <p class="text-gray-600 text-sm">Sends alerts, emails, etc.</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-4 bg-blue-50 rounded-lg shadow-sm">
                <svg class="h-8 w-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Payment Service</h3>
                    <p class="text-gray-600 text-sm">Handles billing (for clients and their customers).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monetization Strategy Section -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Monetization Strategy (Plans)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-purple-50 p-6 rounded-lg shadow-sm text-center">
                <h3 class="font-bold text-xl text-purple-700 mb-2">Free Trial</h3>
                <p class="text-3xl font-extrabold text-purple-900 mb-3">₹0</p>
                <p class="text-gray-600 text-sm mb-4">14 days</p>
                <ul class="text-gray-700 text-left space-y-1">
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Basic dashboard</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Manual revenue entry</li>
                </ul>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg shadow-sm text-center">
                <h3 class="font-bold text-xl text-purple-700 mb-2">Starter</h3>
                <p class="text-3xl font-extrabold text-purple-900 mb-3">₹499</p>
                <p class="text-gray-600 text-sm mb-4">/month</p>
                <ul class="text-gray-700 text-left space-y-1">
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> All Free Trial features</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Auto invoicing</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Analytics</li>
                </ul>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg shadow-sm text-center">
                <h3 class="font-bold text-xl text-purple-700 mb-2">Premium</h3>
                <p class="text-3xl font-extrabold text-purple-900 mb-3">₹999</p>
                <p class="text-gray-600 text-sm mb-4">/month</p>
                <ul class="text-gray-700 text-left space-y-1">
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> All Starter features</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Notifications</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> API integration</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Data export</li>
                </ul>
            </div>
            <div class="bg-purple-50 p-6 rounded-lg shadow-sm text-center">
                <h3 class="font-bold text-xl text-purple-700 mb-2">Enterprise</h3>
                <p class="text-3xl font-extrabold text-purple-900 mb-3">₹4,999</p>
                <p class="text-gray-600 text-sm mb-4">/month</p>
                <ul class="text-gray-700 text-left space-y-1">
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> All Premium features</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Multi-branch support</li>
                    <li class="flex items-center"><svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Priority customer care</li>
                </ul>
            </div>
        </div>
    </div>

@endsection