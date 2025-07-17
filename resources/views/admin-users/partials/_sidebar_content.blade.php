{{-- Clinic Admin/Staff Specific Sidebar Content --}}

@php
    // Ensure $webUser is available for client_id in links
    $webUser = Auth::guard('web')->user();
@endphp

{{-- Dashboard Home --}}
<a href="{{ route('admin.dashboard') }}"
   class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    <span>Clinic Dashboard</span>
</a>

{{-- Revenue Tracking --}}
@if ($webUser && $webUser->hasPermission('view_revenue'))
    <a href="{{ route('revenue.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        <span>Revenue Tracking</span>
    </a>
@endif

{{-- Inventory Management --}}
@if ($webUser && $webUser->hasPermission('view_medicines'))
    <a href="{{ route('medicines.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10l2 2h10l2-2V7M6 7h12M6 7V5a2 2 0 012-2h4a2 2 0 012 2v2M6 7h12"></path></svg>
        <span>Inventory Management</span>
    </a>
@endif

{{-- Sales & Billing --}}
@if ($webUser && $webUser->hasPermission('view_sales'))
    <a href="{{ route('sales.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
        <span>Sales & Billing</span>
    </a>
@endif

{{-- User Management --}}
@if ($webUser && $webUser->hasPermission('view_users'))
    <a href="{{ route('users.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <span>User Management</span>
    </a>
@endif

{{-- Role Management (Clinic-level roles) --}}
@if ($webUser && $webUser->hasPermission('view_roles'))
    <a href="{{ route('roles.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h3m-3 0h-3"></path></svg>
        <span>Role Management</span>
    </a>
@endif

{{-- Client's Branches (Clinic Admin/Staff Only) --}}
@if ($webUser && $webUser->hasPermission('view_branches') && $webUser->client_id)
    <a href="{{ route('branches.index', $webUser->client_id) }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
        <span>Branch Management</span>
    </a>
@endif

{{-- Client's Service Types (Clinic Admin/Staff Only) --}}
@if ($webUser && $webUser->hasPermission('view_service_types') && $webUser->client_id)
    <a href="{{ route('service-types.index', $webUser->client_id) }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
        <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        <span>Service Type Management</span>
    </a>
@endif
