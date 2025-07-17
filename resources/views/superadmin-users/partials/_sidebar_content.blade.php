{{-- SuperAdmin Specific Sidebar Content --}}

{{-- Dashboard Home --}}
<a href="{{ route('superadmin.dashboard') }}"
   class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    <span>SuperAdmin Dashboard</span>
</a>

{{-- Client Management (SuperAdmin Only) --}}
<a href="{{ route('superadmin.clients.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    <span>Client Management</span>
</a>

{{-- Revenue Tracking --}}
<a href="{{ route('superadmin.revenue.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
    <span>Revenue Tracking</span>
</a>

{{-- Inventory Management --}}
<a href="{{ route('superadmin.medicines.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10l2 2h10l2-2V7M6 7h12M6 7V5a2 2 0 012-2h4a2 2 0 012 2v2M6 7h12"></path></svg>
    <span>Inventory Management</span>
</a>

{{-- Sales & Billing --}}
<a href="{{ route('superadmin.sales.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
    <span>Sales & Billing</span>
</a>

{{-- User Management --}}
<a href="{{ route('superadmin.users.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    <span>User Management</span>
</a>

{{-- Role & Permission Management (System-wide and Clinic-level roles) --}}
<a href="{{ route('superadmin.roles.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h3m-3 0h-3"></path></svg>
    <span>Role Management</span>
</a>

{{-- System-wide Permission Management (SuperAdmin Only) --}}
<a href="{{ route('permissions.index') }}" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg hover:bg-indigo-700 transition duration-150 ease-in-out mt-2">
    <svg class="h-6 w-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.002 12.002 0 002 12c0 2.755 1.09 5.312 3.018 7.238A12.002 12.002 0 0012 22c2.755 0 5.312-1.09 7.238-3.018A12.002 12.002 0 0022 12c0-2.755-1.09-5.312-3.018-7.238z"></path></svg>
    <span>Permission Management</span>
</a>
