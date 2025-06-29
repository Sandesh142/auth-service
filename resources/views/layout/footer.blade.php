<!-- Footer -->
<footer class="bg-gray-900 text-white pt-16 pb-10">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:justify-between gap-12">
            <!-- Brand & Description -->
            <div class="flex-1">
                <div class="text-2xl font-bold text-indigo-400 mb-3">PharmaPulse</div>
                <p class="text-gray-400 text-sm max-w-sm">
                    Smarter pharma revenue management, helping you streamline operations and boost profitability with confidence.
                </p>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1">
                <h4 class="text-lg font-semibold mb-4 text-indigo-300">Quick Links</h4>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li><a href="{{ url('/about-us') }}" class="hover:text-white transition">About</a></li>
                    <li><a href="{{ url('/services') }}" class="hover:text-white transition">Services</a></li>
                    <li><a href="{{ url('/contact-us') }}" class="hover:text-white transition">Contact</a></li>
                    <li><a href="{{ url('/faqs') }}" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="{{ url('/privacy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms') }}" class="hover:text-white transition">Terms & Conditions</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="flex-1">
                <h4 class="text-lg font-semibold mb-4 text-indigo-300">Stay Updated</h4>
                <p class="text-gray-400 text-sm mb-4">Subscribe to receive the latest updates and insights.</p>
                <form action="#" method="POST" class="flex flex-col sm:flex-row gap-2">
                    <input type="email" placeholder="Enter your email" class="w-full px-4 py-2 rounded-md text-black" required>
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-gray-700 mt-12 pt-6 text-center text-sm text-gray-500">
            &copy; {{ now()->year }} PharmaPulse. All rights reserved.
        </div>
    </div>
</footer>
