<nav class="sticky top-0 z-50 bg-white shadow-sm py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-700">PharmaPulse</a>

        <div class="hidden md:flex space-x-8 items-center">
            <a href="{{ url('/') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out {{ Request::is('/') ? 'text-indigo-600 font-semibold' : '' }}">
                Home
            </a>
            <a href="{{ url('/about-us') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out {{ Request::is('about-us') ? 'text-indigo-600 font-semibold' : '' }}">
                About Us
            </a>
            <a href="{{ url('/services') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out {{ Request::is('services') ? 'text-indigo-600 font-semibold' : '' }}">
                Services
            </a>
            <a href="{{ url('/contact-us') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out {{ Request::is('contact-us') ? 'text-indigo-600 font-semibold' : '' }}">
                Contact Us
            </a>
            <a href="{{ url('/faqs') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out {{ Request::is('faqs') ? 'text-indigo-600 font-semibold' : '' }}">
                FAQ
            </a>

            @auth('web') {{-- Checks if a user is logged in using the 'web' guard --}}
                <div class="relative">
                    <button id="user-menu-button-desktop" type="button" class="flex items-center space-x-2 text-gray-600 hover:text-indigo-600 focus:outline-none" aria-expanded="false" aria-haspopup="true">
                        {{-- User Profile Picture/Icon --}}
                        @if(Auth::user()->avatar)
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                        @else
                            <svg class="h-8 w-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18.685 19.02A6.5 6.5 0 0112 21.5a6.5 6.5 0 01-6.685-2.48A6.476 6.476 0 015 13.75a6.5 6.5 0 0111.45-4.492A6.5 6.5 0 0112 5.5c-3.59 0-6.5 2.91-6.5 6.5 0 1.25.378 2.428 1.03 3.447zM12 12a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <span class="font-medium hidden sm:inline">{{ Auth::user()->first_name ?? Auth::user()->name }}</span>
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="user-menu-dropdown-desktop" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button-desktop" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">Dashboard</a>
                            {{-- Assuming these routes exist for user settings/account --}}
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
            @else
                <a href="{{ route('admin.login') }}" class="cta-button-primary px-4 py-2 text-sm">Login</a>
            @endauth
        </div>

        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-gray-600 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-white py-4 shadow-md">
        <div class="flex flex-col items-center space-y-4">
            <a href="{{ url('/') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 block {{ Request::is('/') ? 'text-indigo-600 font-semibold' : '' }}">
                Home
            </a>
            <a href="{{ url('/about-us') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 block {{ Request::is('about-us') ? 'text-indigo-600 font-semibold' : '' }}">
                About Us
            </a>
            <a href="{{ url('/services') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 block {{ Request::is('services') ? 'text-indigo-600 font-semibold' : '' }}">
                Services
            </a>
            <a href="{{ url('/contact-us') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 block {{ Request::is('contact-us') ? 'text-indigo-600 font-semibold' : '' }}">
                Contact Us
            </a>
            <a href="{{ url('/faqs') }}"
               class="nav-link text-gray-600 hover:text-indigo-600 block {{ Request::is('faqs') ? 'text-indigo-600 font-semibold' : '' }}">
                FAQ
            </a>

            @auth('web')
                <a href="{{ route('admin.dashboard') }}" class="cta-button-secondary w-4/5">Dashboard</a>
                <a href="#" class="cta-button-secondary w-4/5">Settings</a>
                <a href="#" class="cta-button-secondary w-4/5">Account</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="w-4/5">
                    @csrf
                    <button type="submit" class="cta-button-secondary w-full text-left">Logout</button>
                </form>
            @else
                <a href="{{ route('admin.login') }}" class="cta-button-primary w-4/5">Login</a>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });
        }

        // Desktop User Menu Dropdown Toggle
        const userMenuButtonDesktop = document.getElementById('user-menu-button-desktop');
        const userMenuDropdownDesktop = document.getElementById('user-menu-dropdown-desktop');

        if (userMenuButtonDesktop && userMenuDropdownDesktop) {
            userMenuButtonDesktop.addEventListener('click', function () {
                userMenuDropdownDesktop.classList.toggle('hidden');
            });

            // Close the dropdown if the user clicks outside of it
            document.addEventListener('click', function(event) {
                if (!userMenuDropdownDesktop.contains(event.target) && !userMenuButtonDesktop.contains(event.target)) {
                    userMenuDropdownDesktop.classList.add('hidden');
                }
            });
        }
    });
</script>