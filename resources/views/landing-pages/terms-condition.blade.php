<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions — PharmaPulse</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/custom_styles/style.css') }}">
</head>
<body>

    @include('layouts.header')

    <main>
        <!-- Terms of Service Section -->
        <section id="terms" class="section-padding bg-white py-12">
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
