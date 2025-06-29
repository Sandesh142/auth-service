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
    <link rel="stylesheet" href="{{ asset('assets/custom_styles/style.css') }}">
    <style>
        #home-hero {
            background-image: url('{{ asset('assets/custom_images/hero-pharmapulse.png') }}');
        }
    </style>
</head>
<body>

    @include('layout.header')

    <main>
        <!-- 1. Home / Landing Page Section -->
        <section id="home-hero" class="text-left flex items-center min-h-screen relative">
            <div class="absolute inset-0 opacity-50 z-0"></div>
            <div class="container z-10 relative">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-8">
                        Smarter <span class="text-indigo-300">Pharma Revenue Management</span>
                    </h1>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6">
                        <a href="#" class="cta-button-primary">
                            Get Started &rarr;
                        </a>
                        <a href="services.html" class="cta-button-secondary">
                            Discover Features
                        </a>
                    </div>
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
