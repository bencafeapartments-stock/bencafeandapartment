<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Cafe & Apartment Management System</title>


    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-100 min-h-screen">
    <div class="min-h-screen flex">

        <div class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">

                <div class="text-center">
                    <div
                        class="mx-auto h-24 w-24 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-building text-white text-3xl"></i>
                    </div>
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                        Welcome Back
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Sign in to your Cafe & Apartment Management account
                    </p>
                </div>


                <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">

                        @if ($errors->any())
                            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-circle text-red-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif


                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-2 text-gray-400"></i>Email Address
                            </label>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-all duration-200"
                                placeholder="Enter your email address">
                        </div>


                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                            </label>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition-all duration-200"
                                placeholder="Enter your password">
                        </div>


                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-900">
                                    Remember me
                                </label>
                            </div>

                            <div class="text-sm">
                                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Forgot your password?
                                </a>
                            </div>
                        </div>


                        <div>
                            <button type="submit"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <i class="fas fa-sign-in-alt text-indigo-300 group-hover:text-indigo-200"></i>
                                </span>
                                Sign in to your account
                            </button>
                        </div>
                    </div>
                </form>



                <!-- Footer -->
                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        © {{ date('Y') }} Cafe & Apartment Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Hero Section -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-center h-full p-12 text-white">
                <div class="max-w-lg">
                    <h1 class="text-4xl font-bold mb-6">
                        Modern Apartment & Cafe Management
                    </h1>
                    <p class="text-xl mb-8 text-blue-100">
                        Streamline your property management with our integrated system for apartments and cafe services.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-building text-white"></i>
                            </div>
                            <span class="text-blue-100">Comprehensive apartment management</span>
                        </div>

                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-coffee text-white"></i>
                            </div>
                            <span class="text-blue-100">Integrated cafe ordering system</span>
                        </div>

                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-users text-white"></i>
                            </div>
                            <span class="text-blue-100">Multi-role user management</span>
                        </div>

                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <span class="text-blue-100">Real-time analytics and reporting</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-20 right-20 w-32 h-32 bg-white bg-opacity-10 rounded-full"></div>
            <div class="absolute bottom-20 right-32 w-16 h-16 bg-white bg-opacity-10 rounded-full"></div>
            <div class="absolute top-1/2 right-8 w-8 h-8 bg-white bg-opacity-10 rounded-full"></div>
        </div>
    </div>
</body>

</html>
