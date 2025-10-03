<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Cafe & Apartment Management') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sf': ['-apple-system', 'BlinkMacSystemFont', 'SF Pro Display', 'Segoe UI', 'Roboto',
                            'sans-serif'
                        ],
                    },
                    backdropBlur: {
                        'xs': '2px',
                    },
                    animation: {
                        'slide-in': 'slideIn 0.3s ease-out',
                        'fade-in': 'fadeIn 0.2s ease-out',
                        'scale-in': 'scaleIn 0.15s ease-out',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': {
                                transform: 'translateX(-100%)'
                            },
                            '100%': {
                                transform: 'translateX(0)'
                            },
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                        scaleIn: {
                            '0%': {
                                transform: 'scale(0.95)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'scale(1)',
                                opacity: '1'
                            },
                        },
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .glass-morphism {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .hover-lift {
            transition: all 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-1px);
        }

        .text-shadow {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 font-sf antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            @include('components.top-navigation')

            <main class="flex-1 overflow-y-auto p-6 space-y-6">

                @if (session('success'))
                    <div
                        class="glass-morphism bg-green-50/80 border border-green-200/50 text-green-800 px-6 py-4 rounded-2xl flex items-center shadow-lg shadow-green-500/10 backdrop-saturate-150 animate-scale-in">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-500/20 mr-4">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="font-medium">Success!</p>
                            <p class="text-sm opacity-90">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="glass-morphism bg-red-50/80 border border-red-200/50 text-red-800 px-6 py-4 rounded-2xl flex items-center shadow-lg shadow-red-500/10 backdrop-saturate-150 animate-scale-in">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-500/20 mr-4">
                            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="font-medium">Error!</p>
                            <p class="text-sm opacity-90">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="glass-morphism bg-red-50/80 border border-red-200/50 text-red-800 px-6 py-5 rounded-2xl shadow-lg shadow-red-500/10 backdrop-saturate-150 animate-scale-in">
                        <div class="flex items-center mb-4">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-500/20 mr-4">
                                <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-medium">Please fix the following errors:</p>
                            </div>
                        </div>
                        <ul class="space-y-2 ml-14">
                            @foreach ($errors->all() as $error)
                                <li class="flex items-center text-sm">
                                    <div class="w-1.5 h-1.5 bg-red-400 rounded-full mr-3"></div>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/25 backdrop-blur-sm lg:hidden" x-cloak></div>

    @stack('scripts')
</body>

</html>
