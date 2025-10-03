<header class="glass-morphism bg-white/70 border-b border-white/20 shadow-lg shadow-black/5 backdrop-saturate-150">
    <div class="flex items-center justify-between h-16 px-6">
        <button @click="sidebarOpen = !sidebarOpen"
            class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100/80 hover:bg-gray-200/80 text-gray-600 hover:text-gray-800 lg:hidden transition-all duration-200 hover-lift">
            <i class="fas fa-bars text-lg"></i>
        </button>

        <div class="flex-1 lg:ml-6">
            <h1 class="text-2xl font-semibold text-gray-900 text-shadow tracking-tight">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        <div class="flex items-center space-x-3">
            <button
                class="relative flex items-center justify-center w-11 h-11 rounded-full bg-gray-100/80 hover:bg-gray-200/80 text-gray-500 hover:text-gray-700 transition-all duration-200 hover-lift group">
                <i class="fas fa-bell text-lg group-hover:scale-110 transition-transform duration-200"></i>
                <span
                    class="absolute -top-1 -right-1 h-5 w-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full flex items-center justify-center font-medium shadow-lg animate-pulse">
                    3
                </span>
            </button>

            <div class="relative" x-data="{ userMenuOpen: false }">
                <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center space-x-3 px-4 py-2 rounded-full bg-gray-100/80 hover:bg-gray-200/80 text-gray-700 hover:text-gray-900 transition-all duration-200 hover-lift group">
                    <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium text-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <span class="font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                    <i class="fas fa-chevron-down text-xs group-hover:rotate-180 transition-transform duration-200"></i>
                </button>

                <div x-show="userMenuOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95" @click.away="userMenuOpen = false" x-cloak
                    class="absolute right-0 mt-2 w-56 glass-morphism bg-white/90 rounded-2xl shadow-2xl shadow-black/10 border border-white/20 backdrop-saturate-150 z-50 overflow-hidden">

                    <div class="p-3 border-b border-gray-100/50">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="py-2">
                        <a href="#"
                            class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100/50 transition-colors duration-150">
                            <i class="fas fa-user w-5 text-gray-400 mr-3"></i>
                            <span>Profile Settings</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100/50 transition-colors duration-150">
                            <i class="fas fa-cog w-5 text-gray-400 mr-3"></i>
                            <span>Preferences</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100/50 transition-colors duration-150">
                            <i class="fas fa-question-circle w-5 text-gray-400 mr-3"></i>
                            <span>Help & Support</span>
                        </a>
                    </div>

                    <div class="border-t border-gray-100/50 py-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50/50 transition-colors duration-150">
                                <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
