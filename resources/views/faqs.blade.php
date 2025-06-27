<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ — PharmaPulse</title>
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
        /* Style for the plus/minus icon transition */
        .accordion-header svg {
            transition: transform 0.3s ease;
        }
        .accordion-header.active svg {
            transform: rotate(45deg); /* Rotates plus to become an X/minus */
        }
    </style>
</head>
<body>

    @include('layout.header')

    <main>
        <!-- FAQ Section -->
        <section id="faq" class="section-padding bg-white py-12">
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
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></path></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            Yes! We offer a 14-day free trial—no credit card required.
                        </div>
                    </div>
                    <!-- FAQ Item 3 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">Which payment gateways do you support?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></path></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            We support Stripe and Razorpay out of the box.
                        </div>
                    </div>
                    <!-- FAQ Item 4 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">Can I use it on multiple branches?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></path></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            Yes—with an Enterprise plan, you can manage unlimited branches.
                        </div>
                    </div>
                    <!-- FAQ Item 5 -->
                    <div class="border-b border-gray-200 py-4">
                        <button class="accordion-header flex justify-between items-center w-full focus:outline-none py-2">
                            <span class="text-lg font-medium text-gray-900">How secure is my data?</span>
                            <svg class="w-6 h-6 text-gray-500 transform transition-transform duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></path></svg>
                        </button>
                        <div class="accordion-content hidden text-gray-600 pt-2 pb-4">
                            All data is encrypted in transit (HTTPS) and at rest. Role-based access ensures security.
                        </div>
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

        // FAQ Accordion functionality
        document.querySelectorAll('.accordion-header').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const svg = button.querySelector('svg');

                // Toggle active class on header for icon rotation
                button.classList.toggle('active');
                content.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
