<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SuperAdmin Forgot Password — PharmaPulse</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

    <!-- Main Forgot Password Container (Two Columns, Full Screen) -->
    <div class="flex flex-col md:flex-row w-full min-h-screen">
        <!-- Left Section - Visual/Illustration Display -->
        <div class="relative md:w-3/5 bg-gradient-to-br from-indigo-600 to-purple-700 p-8 flex items-center justify-center text-white text-center overflow-hidden">
            <!-- Background Image -->
            <img src="https://placehold.co/1200x800/222222/FFFFFF/png?text=PharmaPulse+Security"
                 alt="Security Background"
                 class="absolute inset-0 w-full h-full object-cover opacity-30 animated-illustration">
            {{--
                Replace with your actual project-related image. Examples:
                - An image symbolizing security, data protection, or a locked system.
                - Placeholder example: https://placehold.co/1200x800/222222/FFFFFF/png?text=PharmaPulse+Security
            --}}

            <div class="relative z-10 space-y-6">
                <h2 class="text-4xl font-extrabold leading-tight">
                    Reset Your <br><span class="text-indigo-200">SuperAdmin</span> Password
                </h2>
                <p class="text-indigo-100 text-lg max-w-sm mx-auto">
                    We'll help you get back into your SuperAdmin account securely.
                </p>
                <div class="mt-8">
                    <!-- Advanced SVG Illustration related to security/lock -->
                    <svg class="w-48 h-48 mx-auto text-indigo-200" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 17a2 2 0 002-2v-3a2 2 0 00-2-2H8a2 2 0 00-2 2v3a2 2 0 002 2h4zm-1-9V6a3 3 0 016 0v2h2V6a5 5 0 00-10 0v2h2zM4 10a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V10z"/>
                    </svg>
                </div>
            </div>
            <!-- Subtle background pattern or overlay -->
            <div class="absolute inset-0 bg-black opacity-10 pointer-events-none"></div>
        </div>

        <!-- Right Section - Forgot Password Form -->
        <div class="w-full md:w-2/5 bg-white p-4 md:p-8 flex flex-col justify-center shadow-lg md:shadow-none">
            <div class="max-w-md mx-auto w-full">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">SuperAdmin Forgot Password</h2>

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

                <p class="mb-6 text-sm text-gray-600 text-center">
                    Enter your email address below and we'll send you a link to reset your SuperAdmin password.
                </p>

                <form method="POST" action="{{ route('superadmin.password.email') }}" class="space-y-4">
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

                    <button type="submit" class="w-full bg-indigo-600 text-white py-1.5 px-4 rounded-lg hover:bg-indigo-700 transition duration-300 font-semibold shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Email Password Reset Link
                    </button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-600 text-sm">
                        Remember your password? <a href="{{ route('superadmin.login') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-200">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
