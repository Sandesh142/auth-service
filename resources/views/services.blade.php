<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services & Features — PharmaPulse</title>
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
            /* Consistent horizontal padding for better spacing */
            @apply py-16 px-6 sm:px-10 lg:px-16;
        }
        .container {
            /* Consistent max-width for content to feel more balanced on large screens */
            @apply max-w-screen-2xl mx-auto;
            max-width: 1140px !important;
            margin: 0 auto;
        }
        .cta-button-primary {
            @apply inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-300 ease-in-out shadow-lg;
        }
        /* Sticky Nav Highlight */
        .nav-link.active {
            @apply text-indigo-600 font-semibold;
            color: #4f46e5;
        }
    </style>
</head>
<body>

    @include('layout.header')

    <main>
        <!-- Services / Features Section -->
        <section id="services" class="section-padding bg-white d-flex py-12">
            <div class="container text-center space-y-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900">What We Offer</h2>
                <p class="text-lg sm:text-xl text-gray-700 max-w-3xl mx-auto">
                    Tailored solutions for every healthcare provider.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature Card 1: Revenue Tracking -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Revenue Tracking</h3>
                        <p class="text-gray-600">Monitor daily, weekly, monthly income by service type.</p>
                    </div>
                    <!-- Feature Card 2: Service Management -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Service Management</h3>
                        <p class="text-gray-600">Catalog meds, lab tests, consultations in one panel.</p>
                    </div>
                    <!-- Feature Card 3: Invoicing -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Invoicing</h3>
                        <p class="text-gray-600">Auto-generate PDF bills, send via email or print.</p>
                    </div>
                    <!-- Feature Card 4: Analytics & Forecasting -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Analytics & Forecasting</h3>
                        <p class="text-gray-600">Trends, forecasts, service breakdowns for insights.</p>
                    </div>
                    <!-- Feature Card 5: Notifications & Reminders -->
                    <div class="bg-indigo-50 p-8 rounded-xl shadow-md text-center transform hover:scale-105 transition duration-300">
                        <div class="flex justify-center items-center mb-4 text-indigo-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17l-3 3m0 0l-3-3m3 3V10m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Notifications & Reminders</h3>
                        <p class="text-gray-600">Low revenue alerts, late invoice prompts, and more.</p>
                    </div>
                    <!-- Feature Card 6: Payments -->
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
            const currentPath = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                link.classList.remove('active'); // Ensure no hardcoded active class remains
                const linkPath = link.getAttribute('href').split('/').pop();
                // Compare current path with link path, handling index.html correctly
                if (currentPath === linkPath || (currentPath === '' && linkPath === 'index.html')) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
