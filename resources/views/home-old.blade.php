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
        }
        .cta-button-primary {
            @apply inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-300 ease-in-out shadow-lg;
        }
        .cta-button-secondary {
            @apply inline-flex items-center justify-center px-8 py-3 border border-indigo-600 text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10 transition duration-300 ease-in-out shadow-lg;
        }
        /* Sticky Nav Highlight */
        .nav-link.active {
            @apply text-indigo-600 font-semibold;
        }
    </style>
</head>
<body>

    <!-- Sticky Navigation Bar -->
    <nav class="sticky top-0 z-50 bg-white shadow-sm py-4">
        <div class="container flex justify-between items-center">
            <!-- Logo -->
            <a href="#home" class="text-2xl font-bold text-indigo-700">PharmaPulse</a>
            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-8">
                <a href="#home" class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out">Home</a>
                <a href="#about" class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out">About Us</a>
                <a href="#services" class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out">Services</a>
                <a href="#contact" class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out">Contact Us</a>
                <a href="#faq" class="nav-link text-gray-600 hover:text-indigo-600 transition duration-150 ease-in-out">FAQ</a>
                <a href="#" class="cta-button-primary px-4 py-2 text-sm">Login</a>
            </div>
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white py-4 shadow-md">
            <div class="flex flex-col items-center space-y-4">
                <a href="#home" class="nav-link text-gray-600 hover:text-indigo-600 block">Home</a>
                <a href="#about" class="nav-link text-gray-600 hover:text-indigo-600 block">About Us</a>
                <a href="#services" class="nav-link text-gray-600 hover:text-indigo-600 block">Services</a>
                <a href="#contact" class="nav-link text-gray-600 hover:text-indigo-600 block">Contact Us</a>
                <a href="#faq" class="nav-link text-gray-600 hover:text-indigo-600 block">FAQ</a>
                <a href="#" class="cta-button-primary w-4/5">Login</a>
            </div>
        </div>
    </nav>

    <main>
        <!-- 1. Home / Landing Page Section -->
        <section id="home" class="section-padding bg-gradient-to-br from-indigo-50 to-purple-100 text-center">
            <div class="container space-y-8">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-indigo-900 leading-tight">
                    PharmaPulse — <span class="text-indigo-600">Smarter</span> Pharma Revenue Management
                </h1>
                <p class="text-lg sm:text-xl lg:text-2xl text-gray-600 max-w-3xl mx-auto">
                    Optimize billing, analytics, and payments—all in one cloud-based platform tailored for pharmacies, clinics, labs, and hospitals.
                </p>
                <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 pt-4">
                    <a href="#" class="cta-button-primary">
                        Get Started &rarr;
                        <span class="ml-2 text-sm">(Login / Contact Admin)</span>
                    </a>
                    <a href="#services" class="cta-button-secondary">Discover Features</a>
                </div>

                <!-- Why PharmaPulse? -->
                <div class="pt-16 pb-8">
                    <h2 class="text-3xl sm:text-4xl font-bold text-indigo-800 mb-8">Why PharmaPulse?</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="bg-white p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                            <h3 class="text-xl font-semibold text-indigo-700 mb-2">Unified revenue tracking</h3>
                            <p class="text-gray-600">Centralized view of all your income streams.</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                            <h3 class="text-xl font-semibold text-indigo-700 mb-2">Service-wise dashboards</h3>
                            <p class="text-gray-600">Detailed breakdowns by service type for better insights.</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                            <h3 class="text-xl font-semibold text-indigo-700 mb-2">Automated invoicing & reminders</h3>
                            <p class="text-gray-600">Save time with auto-generated bills and timely alerts.</p>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                            <h3 class="text-xl font-semibold text-indigo-700 mb-2">Built-in payments</h3>
                            <p class="text-gray-600">Seamlessly collect payments directly within the platform.</p>
                        </div>
                    </div>
                </div>

                <!-- How It Works (3-step) -->
                <div class="py-8">
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

                <!-- Key Features Grid -->
                <div class="pt-16 pb-8">
                    <h2 class="text-3xl sm:text-4xl font-bold text-indigo-800 mb-8">Key Features</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                            <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Revenue Dashboard</h3>
                                <p class="text-gray-600">Real-time insights at a glance.</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                            <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Service Management</h3>
                                <p class="text-gray-600">Organize and track all your services.</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                            <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Auto Invoicing</h3>
                                <p class="text-gray-600">Automate your billing process.</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                            <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V10m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Notifications & Alerts</h3>
                                <p class="text-gray-600">Stay informed on important updates.</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                            <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Analytics & Reports</h3>
                                <p class="text-gray-600">Deep dive into your financial data.</p>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-md flex items-center space-x-4 transform hover:scale-105 transition duration-300">
                            <svg class="w-10 h-10 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Payments Integration</h3>
                                <p class="text-gray-600">Seamlessly process payments.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonials -->
                <div class="pt-16 pb-8">
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
            </div>
        </section>

        <!-- 2. About Us Section -->
        <section id="about" class="section-padding bg-gray-50">
            <div class="container text-center space-y-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900">Our Mission</h2>
                <p class="text-lg sm:text-xl text-gray-700 max-w-4xl mx-auto">
                    PharmaPulse was built to empower healthcare providers with transparent, compliant, and actionable revenue insights. Know your margins. Charge timely. Grow efficiently.
                </p>

                <!-- Team / Founders -->
                <div class="pt-8">
                    <h3 class="text-3xl font-bold text-indigo-800 mb-8">Meet the Team</h3>
                    <div class="flex flex-col md:flex-row justify-center space-y-8 md:space-y-0 md:space-x-12">
                        <div class="flex flex-col items-center text-center space-y-4">
                            <img src="https://placehold.co/120x120/E0E7FF/6366F1?text=DRN" alt="Dr. Nikhil Rao" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-indigo-200">
                            <h4 class="text-xl font-semibold text-gray-900">Dr. Nikhil Rao</h4>
                            <p class="text-indigo-600">Founder</p>
                            <p class="text-gray-600">Ex-pharmacist, revenue optimization advocate</p>
                        </div>
                        <div class="flex flex-col items-center text-center space-y-4">
                            <img src="https://placehold.co/120x120/E0E7FF/6366F1?text=PS" alt="Priya Sharma" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-indigo-200">
                            <h4 class="text-xl font-semibold text-gray-900">Priya Sharma</h4>
                            <p class="text-indigo-600">Co-founder & CTO</p>
                            <p class="text-gray-600">Laravel & microservices specialist</p>
                        </div>
                    </div>
                </div>

                <!-- Values -->
                <div class="pt-8">
                    <h3 class="text-3xl font-bold text-indigo-800 mb-8">Our Values</h3>
                    <div class="flex flex-wrap justify-center gap-6">
                        <span class="bg-indigo-100 text-indigo-800 text-lg font-medium px-6 py-3 rounded-full shadow-md">Transparency</span>
                        <span class="bg-indigo-100 text-indigo-800 text-lg font-medium px-6 py-3 rounded-full shadow-md">Simplicity</span>
                        <span class="bg-indigo-100 text-indigo-800 text-lg font-medium px-6 py-3 rounded-full shadow-md">Data-Driven Insights</span>
                        <span class="bg-indigo-100 text-indigo-800 text-lg font-medium px-6 py-3 rounded-full shadow-md">Customer First</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Services / Features Section -->
        <section id="services" class="section-padding bg-white">
            <div class="container text-center space-y-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900">What We Offer</h2>
                <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto">
                    Tailored solutions for every healthcare provider.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature Card 1 -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Revenue Tracking</h3>
                        <p class="text-gray-600">Monitor daily, weekly, monthly income by service type.</p>
                    </div>
                    <!-- Feature Card 2 -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Service Management</h3>
                        <p class="text-gray-600">Catalog meds, lab tests, consultations in one panel.</p>
                    </div>
                    <!-- Feature Card 3 -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Invoicing</h3>
                        <p class="text-gray-600">Auto-generate PDF bills, send via email or print.</p>
                    </div>
                    <!-- Feature Card 4 -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics & Forecasting</h3>
                        <p class="text-gray-600">Trends, forecasts, service breakdowns for insights.</p>
                    </div>
                    <!-- Feature Card 5 -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V10m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Notifications & Reminders</h3>
                        <p class="text-gray-600">Low revenue alerts, late invoice prompts, and more.</p>
                    </div>
                    <!-- Feature Card 6 -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Payments</h3>
                        <p class="text-gray-600">Integrated billing via Razorpay or Stripe for convenience.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Contact Us Section -->
        <section id="contact" class="section-padding bg-gray-50">
            <div class="container text-center space-y-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900">Let’s Connect</h2>
                <p class="text-lg sm:text-xl text-gray-700 max-w-2xl mx-auto">
                    Questions, feedback, or demo requests? We’re here to help.
                </p>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    <!-- Contact Form -->
                    <div class="bg-white p-8 rounded-xl shadow-md text-left">
                        <h3 class="text-2xl font-bold text-indigo-800 mb-6">Send us a message</h3>
                        <form class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Your Name">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="you@example.com">
                            </div>
                            <div>
                                <label for="organization" class="block text-sm font-medium text-gray-700">Organization</label>
                                <input type="text" id="organization" name="organization" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Your Company/Clinic">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea id="message" name="message" rows="5" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="How can we help you?"></textarea>
                            </div>
                            <button type="submit" class="cta-button-primary w-full">Send Message</button>
                        </form>
                    </div>

                    <!-- Contact Info & Map -->
                    <div class="bg-white p-8 rounded-xl shadow-md text-left space-y-8">
                        <div>
                            <h3 class="text-2xl font-bold text-indigo-800 mb-4">Contact Information</h3>
                            <p class="flex items-center text-gray-700 mb-2">
                                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-9 6h.01M3 20h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <a href="mailto:support@pharmapulse.com" class="hover:underline">support@pharmapulse.com</a>
                            </p>
                            <p class="flex items-center text-gray-700">
                                <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                +91 98765 43210
                            </p>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-indigo-800 mb-4">Our Location</h3>
                            <p class="text-gray-700 mb-4">123 Pharma Road, Health City, Nagpur, India</p>
                            <!-- Google Map Embed Placeholder -->
                            <div class="bg-gray-200 h-64 rounded-md overflow-hidden shadow-inner flex items-center justify-center text-gray-500">
                                <p>Google Map Embed Placeholder</p>
                                <!-- Replace with actual Google Maps iframe -->
                                <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.258804683058!2d79.08815191493202!3d21.14486658593452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd4c062a4a34b2f%3A0x6a0a0b0a0a0a0a0a!2sNagpur%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1678901234567!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. FAQ Section -->
        <section id="faq" class="section-padding bg-white">
            <div class="container text-center space-y-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900">Frequently Asked Questions</h2>

                <div class="max-w-3xl mx-auto text-left">
                    <!-- FAQ Item 1 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">What types of providers can use PharmaPulse?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            Pharmacies, diagnostic labs, clinics, hospitals—anywhere you track revenue per service.
                        </div>
                    </div>
                    <!-- FAQ Item 2 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">Can I try it before paying?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            Yes! We offer a 14-day free trial—no credit card required.
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">Which payment gateways do you support?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            We support Stripe and Razorpay out of the box.
                        </div>
                    </div>
                    <!-- FAQ Item 4 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">Can I use it on multiple branches?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            Yes—with an Enterprise plan, you can manage unlimited branches.
                        </div>
                    </div>
                    <!-- FAQ Item 5 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">How secure is my data?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            All data is encrypted in transit (HTTPS) and at rest. Role-based access ensures security.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. Privacy Policy Section -->
        <section id="privacy" class="section-padding bg-gray-50">
            <div class="container text-left space-y-8">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900 text-center">Privacy Policy</h2>
                <div class="bg-white p-8 rounded-xl shadow-md space-y-6">
                    <h3 class="text-2xl font-bold text-indigo-800">1. Introduction & Definitions</h3>
                    <p class="text-gray-700">
                        Welcome to PharmaPulse's Privacy Policy. This policy describes how PharmaPulse collects, uses, and shares your personal information. By using our services, you agree to the collection and use of information in accordance with this policy.
                    </p>
                    <h3 class="text-2xl font-bold text-indigo-800">2. Data We Collect</h3>
                    <p class="text-gray-700">
                        We collect various types of information to provide and improve our service to you. This includes:
                        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
                            <li><strong>Personal Data:</strong> Email address, first name, last name, phone number, address, etc.</li>
                            <li><strong>Usage Data:</strong> Information on how the service is accessed and used (e.g., IP address, browser type, pages visited).</li>
                            <li><strong>Billing Data:</strong> Payment details, transaction history.</li>
                        </ul>
                    </p>
                    <h3 class="text-2xl font-bold text-indigo-800">3. How We Use It</h3>
                    <p class="text-gray-700">
                        PharmaPulse uses the collected data for various purposes:
                        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
                            <li>To provide and maintain our service</li>
                            <li>To notify you about changes to our service</li>
                            <li>To allow you to participate in interactive features when you choose to do so</li>
                            <li>To provide customer support</li>
                            <li>To monitor the usage of the service</li>
                            <li>To detect, prevent and address technical issues</li>
                            <li>To provide you with news, special offers and general information about other goods, services and events which we offer that are similar to those that you have already purchased or enquired about unless you have opted not to receive such information</li>
                        </ul>
                    </p>
                    <h3 class="text-2xl font-bold text-indigo-800">4. Cookies & Tracking</h3>
                    <p class="text-gray-700">
                        We use cookies and similar tracking technologies to track the activity on our Service and hold certain information. Cookies are files with a small amount of data which may include an anonymous unique identifier.
                    </p>
                    <h3 class="text-2xl font-bold text-indigo-800">5. Your Rights</h3>
                    <p class="text-gray-700">
                        You have the right to access, update, or delete the information we have on you. You also have the right to data portability and to withdraw consent.
                    </p>
                    <h3 class="text-2xl font-bold text-indigo-800">6. Contact for Privacy Concerns</h3>
                    <p class="text-gray-700">
                        If you have any questions about this Privacy Policy, please contact us:
                        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
                            <li>By email: support@pharmapulse.com</li>
                            <li>By phone number: +91 98765 43210</li>
                        </ul>
                    </p>
                </div>
            </div>
        </section>

        <!-- 7. Terms of Service Section -->
        <section id="terms" class="section-padding bg-white">
            <div class="container text-left space-y-8">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900 text-center">Terms & Conditions</h2>
                <div class="bg-indigo-50 p-8 rounded-xl shadow-md space-y-6 max-h-[600px] overflow-y-auto custom-scrollbar">
                    <h3 class="text-2xl font-bold text-indigo-800">1. Acceptance & Definitions</h3>
                    <p class="text-gray-700">
                        These Terms and Conditions ("Terms") govern your use of the PharmaPulse website and services (the "Service"). By accessing or using the Service, you agree to be bound by these Terms. If you disagree with any part of the terms then you may not access the Service.
                    </p>
                    <p class="text-gray-700">
                        "Service" refers to the PharmaPulse cloud-based revenue management platform. "User," "You," and "Your" refer to the individual or entity accessing or using the Service. "We," "Us," and "Our" refer to PharmaPulse.
                    </p>

                    <h3 class="text-2xl font-bold text-indigo-800">2. License and Access</h3>
                    <p class="text-gray-700">
                        Subject to these Terms, PharmaPulse grants you a limited, non-exclusive, non-transferable, revocable license to use the Service solely for your internal business purposes. This license does not include any resale or commercial use of the Service or its contents.
                    </p>

                    <h3 class="text-2xl font-bold text-indigo-800">3. User Obligations</h3>
                    <p class="text-gray-700">
                        As a user, you agree to:
                        <ul class="list-disc list-inside ml-4 mt-2 space-y-1">
                            <li>Provide accurate and complete information during registration and use of the Service.</li>
                            <li>Maintain the confidentiality of your account credentials.</li>
                            <li>Use the Service only for lawful purposes and in compliance with all applicable laws and regulations.</li>
                            <li>Not to engage in any activity that could harm, disable, overburden, or impair the Service.</li>
                            <li>Not to copy, modify, distribute, or reverse engineer any part of the Service.</li>
                        </ul>
                    </p>

                    <h3 class="text-2xl font-bold text-indigo-800">4. Payments & Billing</h3>
                    <p class="text-gray-700">
                        Certain parts of the Service are billed on a subscription basis ("Subscription(s)"). You will be billed in advance on a recurring and periodic basis ("Billing Cycle"). Billing cycles are set either on a monthly or annual basis, depending on the type of subscription plan you select when purchasing a Subscription.
                    </p>
                    <p class="text-gray-700">
                        At the end of each Billing Cycle, your Subscription will automatically renew under the exact same conditions unless you cancel it or PharmaPulse cancels it. You may cancel your Subscription renewal either through your online account management page or by contacting PharmaPulse customer support.
                    </p>

                    <h3 class="text-2xl font-bold text-indigo-800">5. Termination & Suspension</h3>
                    <p class="text-gray-700">
                        We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms. Upon termination, your right to use the Service will immediately cease.
                    </p>

                    <h3 class="text-2xl font-bold text-indigo-800">6. Disclaimers & Liability</h3>
                    <p class="text-gray-700">
                        The Service is provided on an "AS IS" and "AS AVAILABLE" basis. PharmaPulse makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.
                    </p>
                    <p class="text-gray-700">
                        In no event shall PharmaPulse or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on PharmaPulse’s website, even if PharmaPulse or a PharmaPulse authorized representative has been notified orally or in writing of the possibility of such damage.
                    </p>

                    <h3 class="text-2xl font-bold text-indigo-800">7. Governing Law</h3>
                    <p class="text-gray-700">
                        These Terms shall be governed and construed in accordance with the laws of India, without regard to its conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.
                    </p>
                    <p class="text-gray-700">
                        This custom-scrollbar class is purely for visual styling within the HTML preview. In a real application, you might use a JavaScript library or ensure cross-browser compatibility for scrollbar styling.
                    </p>
                </div>
            </div>
        </section>

        <!-- 8. 404 Not Found Section (as a section within the single page) -->
        <section id="not-found" class="section-padding bg-gradient-to-br from-red-50 to-orange-100 text-center">
            <div class="container space-y-6">
                <h2 class="text-6xl sm:text-7xl lg:text-8xl font-extrabold text-red-700">Oops!</h2>
                <h3 class="text-3xl sm:text-4xl font-bold text-red-800">Page Not Found</h3>
                <p class="text-lg sm:text-xl text-gray-600 max-w-xl mx-auto">
                    The page you’re looking for doesn’t exist or has been moved.
                </p>
                <a href="#home" class="cta-button-primary bg-red-600 hover:bg-red-700">Take me home &rarr;</a>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container flex flex-col md:flex-row justify-between items-center text-center md:text-left space-y-6 md:space-y-0">
            <div class="text-lg font-bold text-indigo-300">PharmaPulse</div>
            <div class="flex flex-wrap justify-center md:justify-start space-x-6">
                <a href="#about" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">About</a>
                <a href="#services" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Services</a>
                <a href="#contact" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Contact</a>
                <a href="#faq" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">FAQ</a>
                <a href="#privacy" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Privacy</a>
                <a href="#terms" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Terms</a>
            </div>
            <p class="text-gray-400 text-sm">&copy; 2025 PharmaPulse. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                // Close mobile menu after clicking a link
                if (window.innerWidth < 768) { // md breakpoint for Tailwind
                    const mobileMenu = document.getElementById('mobile-menu');
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // FAQ Accordion functionality
        document.querySelectorAll('.accordion-header').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const svg = button.querySelector('svg');

                content.classList.toggle('hidden');
                if (content.classList.contains('hidden')) {
                    svg.innerHTML = '<path d="M12 5v14M5 12h14"/>'; // Plus icon
                } else {
                    svg.innerHTML = '<path d="M5 12h14"/>'; // Minus icon
                }
            });
        });

        // Highlight active nav link on scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 100) { // Adjust offset as needed
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.href.includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Initial active link highlight on load
        window.dispatchEvent(new Event('scroll'));

    </script>
</body>
</html>
