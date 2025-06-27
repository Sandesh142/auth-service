<!-- Sticky Navigation Bar -->
<nav class="sticky top-0 z-50 bg-white shadow-sm py-4">
    <div class="container flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-700">PharmaPulse</a>

        <!-- Desktop Navigation -->
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
            <a href="{{ url('/login') }}" class="cta-button-primary px-4 py-2 text-sm">Login</a>
        </div>

        <!-- Mobile Menu Button -->
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

    <!-- Mobile Menu -->
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
            <a href="{{ url('/login') }}" class="cta-button-primary w-4/5">Login</a>
        </div>
    </div>
</nav>
