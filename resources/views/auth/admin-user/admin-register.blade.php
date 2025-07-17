<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register — PharmaPulse</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons (e.g., eye icon for password toggle) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* A subtle gradient background for the entire page */
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            display: flex;
            min-height: 100vh;
            margin: 0;
        }

        /* Custom animation for the background image/illustration */
        @keyframes pulse-fade {
            0% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.02); }
            100% { opacity: 0.8; transform: scale(1); }
        }

        .animated-illustration {
            animation: pulse-fade 4s ease-in-out infinite;
        }

        /* Custom styles for input focus */
        .input-field:focus {
            border-color: #6366f1; /* indigo-500 */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.4); /* indigo-500 with opacity */
        }
    </style>
</head>
<body>

    <!-- Main Register Container (Two Columns, Full Screen) -->
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        <!-- Left Section - Visual/Illustration Display -->
        <div class="relative md:w-3/5 bg-gradient-to-br from-indigo-600 to-purple-700 p-8 flex items-center justify-center text-white text-center">
            <div class="space-y-6">
                <h2 class="text-4xl font-extrabold leading-tight">
                    Join <br><span class="text-indigo-200">PharmaPulse</span>
                </h2>
                <p class="text-indigo-100 text-lg max-w-sm mx-auto">
                    Unlock smarter revenue management for your pharmaceutical business.
                </p>
                <div class="mt-8">
                    <!-- Advanced SVG Illustration related to data/health -->
                    <svg class="animated-illustration w-48 h-48 mx-auto text-indigo-200" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15H9V9h2v8zm4 0h-2V7h2v10zM12 5.5a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/>
                    </svg>
                </div>
            </div>
            <!-- Subtle background pattern or overlay -->
            <div class="absolute inset-0 bg-black opacity-10 pointer-events-none"></div>
        </div>

        <!-- Right Section - Register Form -->
        {{-- Reduced padding from p-6 md:p-10 to p-4 md:p-8 --}}
        <div class="w-full md:w-2/5 bg-white p-4 md:p-8 flex flex-col justify-center shadow-lg md:shadow-none">
            <div class="max-w-md mx-auto w-full"> {{-- This wrapper constrains form width --}}
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Register Your Admin Account</h2>

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

                {{-- Reduced space-y-6 to space-y-4 --}}
                <form method="POST" action="{{ route('admin.register.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-gray-700 text-sm font-medium mb-2">Full Name</label>
                        {{-- Reduced py-2 to py-1.5 --}}
                        <input type="text" id="name" name="name"
                               class="input-field w-full px-4 py-1.5 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('name') border-red-500 @enderror"
                               value="{{ old('name') }}" required autofocus placeholder="John Doe">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                        {{-- Reduced py-2 to py-1.5 --}}
                        <input type="email" id="email" name="email"
                               class="input-field w-full px-4 py-1.5 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" required placeholder="your.email@example.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        {{-- Reduced py-2 to py-1.5 --}}
                        <input type="password" id="password" name="password"
                               class="input-field w-full px-4 py-1.5 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('password') border-red-500 @enderror"
                               required autocomplete="new-password" placeholder="••••••••">
                        {{-- Adjusted mt-5 to mt-4 for button vertical alignment --}}
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 mt-4">
                            <i class="far fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="relative">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                        {{-- Reduced py-2 to py-1.5 --}}
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="input-field w-full px-4 py-1.5 border border-gray-300 rounded-lg focus:outline-none transition duration-200 @error('password_confirmation') border-red-500 @enderror"
                               required autocomplete="new-password" placeholder="••••••••">
                        {{-- Adjusted mt-5 to mt-4 for button vertical alignment --}}
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 mt-4">
                            <i class="far fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Reduced py-2 to py-1.5 --}}
                    <button type="submit" class="w-full bg-indigo-600 text-white py-1.5 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Register
                    </button>
                </form>

                <div class="text-center mt-8">
                    <p class="text-gray-600 text-sm">
                        Already have an account? <a href="{{ route('admin.login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-200">Log in</a>
                    </p>
                    {{-- Optional: Social Register Buttons --}}
                    {{-- Reduced mt-6 to mt-4 and mb-4 to mb-2 --}}
                    <div class="mt-4">
                        <p class="text-gray-500 text-xs uppercase tracking-wider mb-2">Or sign up with</p>
                        <div class="flex justify-center space-x-4">
                            <button type="button" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition duration-200 shadow-md">
                                <i class="fab fa-google"></i>
                            </button>
                            <button type="button" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 text-white hover:bg-gray-900 transition duration-200 shadow-md">
                                <i class="fab fa-github"></i>
                            </button>
                            <button type="button" class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-400 text-white hover:bg-blue-500 transition duration-200 shadow-md">
                                <i class="fab fa-twitter"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div> {{-- End of max-w-md wrapper --}}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password Visibility Toggle for main password field
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

            // Password Visibility Toggle for confirm password field
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordField = document.getElementById('password_confirmation');

            if (toggleConfirmPassword && confirmPasswordField) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordField.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
