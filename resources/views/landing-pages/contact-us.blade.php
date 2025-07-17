<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us — PharmaPulse</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/custom_styles/style.css') }}">
</head>
<body>

    @include('layouts.header')

    <main>
        <!-- Contact Us Section -->
        <section id="contact" class="section-padding bg-gray-50 py-12">
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
                                <!-- You can replace this div with an actual Google Maps iframe. Example: -->
                                <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3721.258804683058!2d79.08815191493202!3d21.14486658593452!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bd4c062a4a34b2f%3A0x6a0a0b0a0a0a0a0a!2sNagpur%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1678901234567!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('layouts.footer')

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
