<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaPulse — Smarter Pharma Revenue Management</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            @apply bg-gray-50 text-gray-800;
        }
        .section-padding {
            @apply py-16 px-4 sm:px-6 lg:px-8;
        }
        .container {
            @apply max-w-7xl mx-auto;
            max-width: 1140px !important;
            margin: 0 auto;
        }
        .cta-button-primary {
            @apply inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-300 ease-in-out shadow-lg;
        }
        .cta-button-secondary {
            @apply inline-flex items-center justify-center px-8 py-3 border border-indigo-600 text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10 transition duration-300 ease-in-out shadow-lg;
        }
        /* Sticky Nav Highlight - Will be handled by JS on multi-page */
        .nav-link.active {
            @apply text-indigo-600 font-semibold;
            color: #4f46e5;
        }

        /* Specific styles for the hero section background image and overlay */
        #home-hero {
            background-image: url('https://placehold.co/1920x1080/6366F1/FFFFFF/png?text=PharmaPulse+Hero+Image'); /* Placeholder Image URL - Replace with your actual image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative; /* Needed for overlay positioning */
            color: white; /* Ensure text is visible against dark overlay */
            min-height: 100vh; /* Make it full screen height */
            display: flex;
            align-items: center; /* Center content vertically */
            justify-content: center; /* Center content horizontally */
        }
        #home-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent dark overlay */
            z-index: 1; /* Place overlay above background image but below content */
        }
        #home-hero .container {
            position: relative;
            z-index: 2; /* Place content above the overlay */
            color: white; /* Ensure text is white over the dark overlay */
        }
        #home-hero .cta-button-primary {
            background-color: #4F46E5; /* Keep primary button color consistent */
            border-color: transparent;
        }
        #home-hero .cta-button-secondary {
            background-color: rgba(255, 255, 255, 0.2); /* Make secondary button transparent white */
            border-color: white;
            color: white;
        }
        #home-hero .cta-button-secondary:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        #home-hero h1, #home-hero p {
            color: white; /* Ensure all text in hero is white */
        }
        #home-hero h1 .text-indigo-600 {
            color: #C7D2FE; /* Lighter indigo for accent text on dark background */
        }
    </style>
</head>
<body>

    @include('layout.header')

    <main>
        <!-- 1. Home / Landing Page Section -->
        <section id="home-hero" class="text-center">
            <div class="container space-y-8 py-24 sm:py-32 lg:py-48">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">
                    PharmaPulse — <span class="text-indigo-600">Smarter</span> Pharma Revenue Management
                </h1>
                <p class="text-lg sm:text-xl lg:text-2xl max-w-3xl mx-auto">
                    Optimize billing, analytics, and payments—all in one cloud-based platform tailored for pharmacies, clinics, labs, and hospitals.
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 pt-4">
                    <a href="#" class="cta-button-primary">
                        Get Started &rarr;
                        <span class="ml-2 text-sm">(Login / Contact Admin)</span>
                    </a>
                    <a href="services.html" class="cta-button-secondary">Discover Features</a>
                </div>
            </div>
        </section>

        <!-- Why PharmaPulse? Section -->
        <section id="why-pharmapulse" class="section-padding bg-white py-12">
            <div class="container text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-indigo-800 mb-8">Why PharmaPulse?</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <h3 class="text-xl font-semibold text-indigo-700 mb-2">Unified revenue tracking</h3>
                        <p class="text-gray-600">Centralized view of all your income streams.</p>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <h3 class="text-xl font-semibold text-indigo-700 mb-2">Service-wise dashboards</h3>
                        <p class="text-gray-600">Detailed breakdowns by service type for better insights.</p>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <h3 class="text-xl font-semibold text-indigo-700 mb-2">Automated invoicing & reminders</h3>
                        <p class="text-gray-600">Save time with auto-generated bills and timely alerts.</p>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <h3 class="text-xl font-semibold text-indigo-700 mb-2">Built-in payments</h3>
                        <p class="text-gray-600">Seamlessly collect payments directly within the platform.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works (3-step) Section -->
        <section id="how-it-works" class="section-padding bg-gray-50 py-12">
            <div class="container text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-indigo-800 mb-8">How It Works</h2>
                <div class="flex flex-col md:flex-row justify-center items-center space-y-8 md:space-y-0 md:space-x-12">
                    <div class="flex flex-col items-center text-center space-y-2">
                        <div class="bg-indigo-600 text-white rounded-full h-12 w-12 flex items-center justify-center text-2xl font-bold shadow-lg">1</div>
                        <p class="text-lg font-medium text-gray-700">Sign up</p>
                        <p class="text-gray-500">Quick & easy registration.</p>
                    </div>
                    <svg class="hidden md:block w-16 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    <div class="flex flex-col items-center text-center space-y-2">
                        <div class="bg-indigo-600 text-white rounded-full h-12 w-12 flex items-center justify-center text-2xl font-bold shadow-lg">2</div>
                        <p class="text-lg font-medium text-gray-700">Add services</p>
                        <p class="text-gray-500">List your offerings and prices.</p>
                    </div>
                    <svg class="hidden md:block w-16 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    <div class="flex flex-col items-center text-center space-y-2">
                        <div class="bg-indigo-600 text-white rounded-full h-12 w-12 flex items-center justify-center text-2xl font-bold shadow-lg">3</div>
                        <p class="text-lg font-medium text-gray-700">Start tracking and invoicing</p>
                        <p class="text-gray-500">Gain insights and streamline billing.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Features Grid Section -->
        <section id="key-features" class="section-padding bg-white py-12">
            <div class="container text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-indigo-800 mb-8">Key Features</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                        <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Revenue Dashboard</h3>
                            <p class="text-gray-600">Real-time insights at a glance.</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                        <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Service Management</h3>
                            <p class="text-gray-600">Organize and track all your services.</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                        <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Auto Invoicing</h3>
                            <p class="text-gray-600">Automate your billing process.</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                        <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V10m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Notifications & Alerts</h3>
                            <p class="text-gray-600">Stay informed on important updates.</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                        <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Analytics & Reports</h3>
                            <p class="text-gray-600">Deep dive into your financial data.</p>
                        </div>
                    </div>
                    <div class="bg-indigo-50 p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                        <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Payments Integration</h3>
                            <p class="text-gray-600">Seamlessly process payments.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="section-padding bg-gray-50 py-12">
            <div class="container text-center">
                <h2 class="text-3xl sm:text-4xl font-bold text-indigo-800 mb-8">What Our Clients Say</h2>
                <div class="flex flex-col md:flex-row justify-center items-center md:space-x-8 space-y-8 md:space-y-0">
                    <div class="bg-white p-8 rounded-xl shadow-lg max-w-lg">
                        <p class="text-lg text-gray-700 italic mb-4">
                            “Since switching to PharmaPulse, we’ve cut invoicing time by 50%.”
                        </p>
                        <p class="font-semibold text-indigo-600">– Clinic Admin</p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    @include('layout.footer')
    
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Highlight active nav link based on current page
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname.split('/').pop(); // Gets 'index.html', 'about.html', etc.
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                link.classList.remove('active'); // Remove active from all first
                const linkPath = link.getAttribute('href').split('/').pop();
                if (currentPath === linkPath || (currentPath === '' && linkPath === 'index.html')) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
