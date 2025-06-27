<!-- Footer -->
<footer class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center text-center md:text-left space-y-6 md:space-y-0">
        <!-- Brand -->
        <div class="text-lg font-bold text-indigo-300">PharmaPulse</div>

        <!-- Navigation Links -->
        <div class="flex flex-wrap justify-center md:justify-start space-x-6">
            <a href="{{ url('/about-us') }}" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">About</a>
            <a href="{{ url('/services') }}" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Services</a>
            <a href="{{ url('/contact-us') }}" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Contact</a>
            <a href="{{ url('/faqs') }}" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">FAQ</a>
            <a href="{{ url('/privacy') }}" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Privacy</a>
            <a href="{{ url('/terms') }}" class="text-gray-300 hover:text-white transition duration-150 ease-in-out">Terms</a>
        </div>

        <!-- Copyright -->
        <p class="text-gray-400 text-sm">&copy; {{ now()->year }} PharmaPulse. All rights reserved.</p>
    </div>
</footer>
