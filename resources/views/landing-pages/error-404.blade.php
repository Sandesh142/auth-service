<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found — PharmaPulse</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/custom_styles/style.css') }}">
</head>
<body class="flex flex-col min-h-screen">

    <!-- Minimal Navigation for 404 page (optional, can be removed if desired) -->
    <nav class="bg-white shadow-sm py-4">
        <div class="container flex justify-between items-center">
            <a href="index.html" class="text-2xl font-bold text-indigo-700">PharmaPulse</a>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center section-padding bg-gradient-to-br from-red-50 to-orange-100">
        <div class="container text-center space-y-8">
            <h1 class="text-6xl sm:text-7xl lg:text-8xl font-extrabold text-red-700">Oops!</h1>
            <h2 class="text-3xl sm:text-4xl font-bold text-red-800">Page Not Found</h2>
            <p class="text-lg sm:text-xl text-gray-600 max-w-xl mx-auto">
                The page you’re looking for doesn’t exist or has been moved.
            </p>
            <a href="index.html" class="cta-button-primary bg-red-600 hover:bg-red-700">Take me home &rarr;</a>
        </div>
    </main>

    <!-- Footer (reused from other pages, simplified for 404) -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container flex flex-col md:flex-row justify-between items-center text-center md:text-left space-y-6 md:space-y-0">
            <div class="text-lg font-bold text-indigo-300">PharmaPulse</div>
            <p class="text-gray-400 text-sm">&copy; 2025 PharmaPulse. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
