<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PharmaPulse Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/custom_styles/style.css') }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background for dashboard */
        }
        /* Custom styles for sidebar active link */
        .sidebar-link.active {
            background-color: #4f46e5; /* indigo-600 */
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar-link.active svg {
            color: #ffffff;
        }
        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            z-index: 30; /* Below sidebar, above content */
            display: none; /* Hidden by default */
        }
        .sidebar-overlay.active {
            display: block;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <header class="bg-white shadow-sm py-4 px-4 md:px-8 flex justify-between items-center fixed top-0 left-0 right-0 z-40">
        <div class="md:hidden">
            <button id="mobile-sidebar-button" class="text-gray-600 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="text-xl md:text-2xl font-bold text-indigo-700">PharmaPulse Dashboard</a>

        <div class="relative">
            <button id="user-menu-button-dashboard" type="button" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 focus:outline-none" aria-expanded="false" aria-haspopup="true">
                {{-- User Profile Picture/Icon --}}
                @if(Auth::guard('web')->check() && Auth::guard('web')->user()->avatar)
                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::guard('web')->user()->avatar) }}" alt="{{ Auth::guard('web')->user()->name }}">
                @else
                    <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M18.685 19.02A6.5 6.5 0 0112 21.5a6.5 6.5 0 01-6.685-2.48A6.476 6.476 0 015 13.75a6.5 6.5 0 0111.45-4.492A6.5 6.5 0 0112 5.5c-3.59 0-6.5 2.91-6.5 6.5 0 1.25.378 2.428 1.03 3.447zM12 12a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span class="font-medium hidden sm:inline">{{ Auth::guard('web')->user()->first_name ?? Auth::guard('web')->user()->name ?? 'User' }}</span>
                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div id="user-menu-dropdown-dashboard" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button-dashboard" tabindex="-1">
                <div class="py-1" role="none">
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Dashboard</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Settings</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Account</a>

                    <form method="POST" action="{{ route('admin.logout') }}" role="none">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1 pt-16"> <div id="sidebar-overlay" class="sidebar-overlay hidden md:hidden"></div>

        @include('layouts.partials.dashboard-sidebar')

        <main id="main-content" class="flex-1 p-6 md:p-8 transition-all duration-300 ease-in-out md:ml-64">
            @yield('content')
        </main>
    </div>

    {{-- Optional: Dashboard-specific footer or general footer --}}
    {{-- @include('layouts.partials.dashboard-footer') --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileSidebarButton = document.getElementById('mobile-sidebar-button');
            const sidebar = document.getElementById('dashboard-sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            // Function to toggle sidebar for mobile
            const toggleMobileSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden'); // Prevent body scroll
            };

            if (mobileSidebarButton && sidebar && mainContent && sidebarOverlay) {
                mobileSidebarButton.addEventListener('click', toggleMobileSidebar);
                sidebarOverlay.addEventListener('click', toggleMobileSidebar); // Close sidebar when overlay is clicked

                // Handle window resize: Adjust layout for desktop/mobile
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) { // md breakpoint
                        sidebar.classList.remove('-translate-x-full'); // Ensure sidebar is visible on desktop
                        sidebarOverlay.classList.add('hidden'); // Ensure overlay is hidden
                        document.body.classList.remove('overflow-hidden'); // Allow body scroll
                        mainContent.classList.add('md:ml-64'); // Ensure main content moves over
                    } else { // Mobile breakpoint
                        mainContent.classList.remove('md:ml-64'); // Remove desktop margin
                        // If sidebar was visible (e.g., resized from desktop), ensure overlay is active
                        if (!sidebar.classList.contains('-translate-x-full')) {
                            sidebarOverlay.classList.remove('hidden');
                            document.body.classList.add('overflow-hidden');
                        }
                    }
                });

                // Initial check for layout on page load
                if (window.innerWidth >= 768) { // md breakpoint
                    sidebar.classList.remove('-translate-x-full'); // Start visible on desktop
                    mainContent.classList.add('md:ml-64'); // Apply margin
                } else {
                    sidebar.classList.add('-translate-x-full'); // Start hidden on mobile
                    mainContent.classList.remove('md:ml-64'); // Ensure no margin
                }
            }

            // Desktop User Menu Dropdown Toggle (in dashboard header)
            const userMenuButtonDesktop = document.getElementById('user-menu-button-dashboard');
            const userMenuDropdownDesktop = document.getElementById('user-menu-dropdown-dashboard');

            if (userMenuButtonDesktop && userMenuDropdownDesktop) {
                userMenuButtonDesktop.addEventListener('click', function (event) {
                    event.stopPropagation(); // Prevent click from bubbling to document and closing dropdown immediately
                    userMenuDropdownDesktop.classList.toggle('hidden');
                });

                // Close the dropdown if the user clicks outside of it
                document.addEventListener('click', function(event) {
                    if (!userMenuDropdownDesktop.contains(event.target) && !userMenuButtonDesktop.contains(event.target)) {
                        userMenuDropdownDesktop.classList.add('hidden');
                    }
                });
            }

            // Highlight active sidebar link based on current URL
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-link');

            sidebarLinks.forEach(link => {
                const linkHref = new URL(link.href).pathname;
                if (currentPath === linkHref) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>