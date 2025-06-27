<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — PharmaPulse</title>
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
            /* Increased horizontal padding for better spacing */
            @apply py-16 px-6 sm:px-10 lg:px-16;
        }
        .container {
            /* Increased max-width for content to feel more balanced on large screens */
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
        <!-- About Us Section -->
        <section id="about" class="section-padding bg-gray-50 py-12">
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
                            <img src="https://placehold.co/120x120/E0E7FF/6366F1?text=ABC" alt="Dr. Nikhil Rao" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-indigo-200">
                            <h4 class="text-xl font-semibold text-gray-900">Coming Soon</h4>
                            <p class="text-indigo-600">Founder</p>
                            <p class="text-gray-600">Ex-pharmacist, revenue optimization advocate</p>
                        </div>
                        <div class="flex flex-col items-center text-center space-y-4">
                            <img src="https://placehold.co/120x120/E0E7FF/6366F1?text=PS" alt="Priya Sharma" class="w-32 h-32 rounded-full object-cover shadow-lg border-4 border-indigo-200">
                            <h4 class="text-xl font-semibold text-gray-900">Coming Soon</h4>
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
                link.classList.remove('active');
                const linkPath = link.getAttribute('href').split('/').pop();
                if (currentPath === linkPath || (currentPath === '' && linkPath === 'index.html')) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
