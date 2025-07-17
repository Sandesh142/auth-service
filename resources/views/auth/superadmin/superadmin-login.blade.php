<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Login — PharmaPulse</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons (e.g., eye icon for password toggle) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        @keyframes pulse-fade {
            0% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.02); }
            100% { opacity: 0.8; transform: scale(1); }
        }
        .animated-illustration {
            animation: pulse-fade 4s ease-in-out infinite;
        }
        .input-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.4);
        }
    </style>
</head>
<body>

    <!-- Main Login Container (Two Columns, Full Screen) -->
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        <!-- Left Section - Visual/Illustration Display -->
        <div class="relative md:w-3/5 bg-gradient-to-br from-indigo-600 to-purple-700 p-8 flex items-center justify-center text-white text-center overflow-hidden">
            <!-- Background Image -->
            <img src="https://placehold.co/1200x800/222222/FFFFFF/png?text=PharmaPulse+SuperAdmin"
                 alt="SuperAdmin Background"
                 class="absolute inset-0 w-full h-full object-cover opacity-30 animated-illustration">
            {{--
                Replace with your actual project-related image. Examples:
                - A high-quality stock photo of a data center, network, or abstract tech.
                - An image representing high-level management or system overview.
                - Placeholder example: https://placehold.co/1200x800/222222/FFFFFF/png?text=PharmaPulse+SuperAdmin
            --}}

            <div class="relative z-10 space-y-6"> {{-- z-10 to ensure text/SVG are above image --}}
                <h2 class="text-4xl font-extrabold leading-tight">
                    Welcome to <br><span class="text-indigo-200">PharmaPulse</span>
                </h2>
                <p class="text-indigo-100 text-lg max-w-sm mx-auto">
                    SuperAdmin Access: Your command center for the system.
                </p>
                <div class="mt-8">
                    <!-- Advanced SVG Illustration related to data/health -->
                    <svg class="w-48 h-48 mx-auto text-indigo-200" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15H9V9h2v8zm4 0h-2V7h2v10zM12 5.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                    </svg>
                </div>
            </div>
            <!-- Subtle background pattern or overlay (kept for stylistic consistency) -->
            <div class="absolute inset-0 bg-black opacity-10 pointer-events-none"></div>
        </div>

        <!-- Right Section - Login Form -->
        <div class="w-full md:w-2/5 bg-white p-4 md:p-8 flex flex-col justify-center shadow-lg md:shadow-none">
            <div class="max-w-md mx-auto w-full">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Sign In to SuperAdmin Account</h2>

                <!-- Session Status Message -->
                @if (session('status'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg animate-fade-in" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg animate-fade-in" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.login.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                        <input type="email" id="email" name="email"
                               class="input-field w-full px-4 py-1.5 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" required autofocus placeholder="your.email@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        <input type="password" id="password" name="password"
                               class="input-field w-full px-4 py-1.5 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('password') border-red-500 @enderror"
                               required autocomplete="current-password" placeholder="••••••••">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 mt-4">
                            <i class="far fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center cursor-pointer">
                            <input type="checkbox" id="remember_me" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>

                        @if (Route::has('superadmin.password.request'))
                            <a class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition duration-200" href="{{ route('superadmin.password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-1.5 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Log in
                    </button>
                </form>

                <div class="text-center mt-8">
                    <p class="text-gray-600 text-sm">
                        {{-- SuperAdmin accounts are typically not publicly registered.
                             If you need a registration, you might add it here,
                             otherwise, this link can be removed or lead to a contact page. --}}
                        Don't have an account? Contact support for SuperAdmin access.
                    </p>
                    {{-- Social Login buttons are usually not applicable for SuperAdmin --}}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('password');

            if (togglePassword && passwordField) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
