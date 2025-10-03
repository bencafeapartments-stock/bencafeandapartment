@php
    $userRole = auth()->user()->role;
    $currentRoute = request()->route()->getName();
    $navigationItems = [
        'owner' => [
            [
                'route' => 'owner.dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'label' => 'Dashboard',
                'roles' => ['owner'],
            ],
            [
                'route' => 'owner.staff.index',
                'icon' => 'fas fa-users',
                'label' => 'Staff Management',
                'roles' => ['owner'],
            ],
            [
                'route' => 'owner.tenants.index',
                'icon' => 'fas fa-user-friends',
                'label' => 'Tenants',
                'roles' => ['owner'],
            ],
            [
                'route' => 'owner.apartments.index',
                'icon' => 'fas fa-building',
                'label' => 'Apartments',
                'roles' => ['owner'],
            ],
            [
                'route' => 'owner.billing.index',
                'icon' => 'fas fa-file-invoice-dollar',
                'label' => 'Billing',
                'roles' => ['owner'],
            ],
            [
                'route' => 'owner.reports',
                'icon' => 'fas fa-chart-bar',
                'label' => 'Reports',
                'roles' => ['owner'],
            ],
        ],
        'staff' => [
            [
                'route' => 'staff.dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'label' => 'Dashboard',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.maintenance.index',
                'icon' => 'fas fa-tools',
                'label' => 'Maintenance',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.orders.index',
                'icon' => 'fas fa-shopping-cart',
                'label' => 'Cafe Orders',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.apartments.index',
                'icon' => 'fas fa-building',
                'label' => 'Apartments',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.menu-items.index',
                'icon' => 'fas fa-utensils',
                'label' => 'Menu Items',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.billing.index',
                'icon' => 'fas fa-file-invoice-dollar',
                'label' => 'Billing',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.tenants.index',
                'icon' => 'fas fa-user-friends',
                'label' => 'Tenants',
                'roles' => ['staff'],
            ],
            [
                'route' => 'staff.reports',
                'icon' => 'fas fa-chart-bar',
                'label' => 'Reports',
                'roles' => ['staff'],
            ],
        ],
        'tenant' => [
            [
                'route' => 'tenant.dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'label' => 'Dashboard',
                'roles' => ['tenant'],
            ],
            [
                'route' => 'tenant.rent.index',
                'icon' => 'fas fa-home',
                'label' => 'Profile',
                'roles' => ['tenant'],
            ],
            [
                'route' => 'tenant.maintenance.index',
                'icon' => 'fas fa-tools',
                'label' => 'Maintenance',
                'roles' => ['tenant'],
            ],
            [
                'route' => 'tenant.orders.index',
                'icon' => 'fas fa-coffee',
                'label' => 'Cafe Orders',
                'roles' => ['tenant'],
            ],
            [
                'route' => 'tenant.billing.index',
                'icon' => 'fas fa-receipt',
                'label' => 'Billing',
                'roles' => ['tenant'],
            ],
            [
                'route' => 'tenant.profile',
                'icon' => 'fas fa-user',
                'label' => 'Profile',
                'roles' => ['tenant'],
            ],
        ],
    ];

    $userNavItems = $navigationItems[$userRole] ?? [];
@endphp

<div class="fixed inset-y-0 left-0 z-50 w-72 glass-morphism bg-white/95 backdrop-saturate-150 shadow-2xl shadow-black/10 border-r border-white/20 transform -translate-x-full transition-all duration-300 ease-out lg:translate-x-0 lg:static lg:inset-0 animate-slide-in flex flex-col"
    :class="{ 'translate-x-0': sidebarOpen }">

    <div
        class="flex items-center justify-center h-20 px-6 glass-morphism bg-gradient-to-r from-blue-500/90 via-blue-600/90 to-indigo-600/90 backdrop-blur-xl border-b border-white/20 shadow-lg">
        <div class="flex items-center group hover-lift cursor-pointer">
            <div
                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4 group-hover:scale-110 transition-all duration-300 shadow-lg">
                <i class="fas fa-building text-white text-xl drop-shadow-sm"></i>
            </div>
            <div>
                <span class="text-white font-bold text-xl tracking-tight drop-shadow-sm">BEN'S</span>
                <p class="text-white/80 text-sm font-medium -mt-1">Cafe & Apartments</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @foreach ($userNavItems as $item)
            @php
                $isActive =
                    str_starts_with($currentRoute, str_replace('.index', '', $item['route'])) ||
                    $currentRoute === $item['route'];

                $routeExists = Route::has($item['route']);
                $url = $routeExists ? route($item['route']) : '#';
            @endphp

            <a href="{{ $url }}"
                class="group flex items-center px-4 py-3 text-sm font-medium rounded-2xl transition-all duration-200 hover-lift relative overflow-hidden
                   @if ($isActive) bg-gradient-to-r from-blue-500/20 to-indigo-500/20 text-blue-700 shadow-lg shadow-blue-500/10 border border-blue-200/50
                   @else
                       text-gray-600 hover:bg-gray-100/80 hover:text-gray-900 border border-transparent hover:border-gray-200/50 hover:shadow-md @endif">

                <div
                    class="flex items-center justify-center w-8 h-8 rounded-xl
                    @if ($isActive) bg-blue-500/20 text-blue-600
                    @else
                        bg-gray-100/80 text-gray-500 group-hover:bg-gray-200/80 group-hover:text-gray-700 @endif
                    transition-all duration-200 group-hover:scale-110 mr-3">
                    <i class="{{ $item['icon'] }} text-sm"></i>
                </div>

                <span class="flex-1 font-medium tracking-tight">{{ $item['label'] }}</span>

                @if (!$routeExists)
                    <span class="px-2 py-1 text-xs font-medium bg-gray-100/80 text-gray-500 rounded-full">Soon</span>
                @endif

                @if ($isActive)
                    <div
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 w-1 h-8 rounded-l-full bg-gradient-to-b from-blue-500 to-indigo-500 shadow-lg">
                    </div>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-gray-100/50 glass-morphism bg-gray-50/50 backdrop-blur-sm">
        <div
            class="flex items-center p-4 rounded-2xl bg-white/80 border border-white/50 shadow-lg hover-lift transition-all duration-200 cursor-pointer group">
            <div class="relative">
                <div
                    class="w-12 h-12 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg group-hover:scale-105 transition-transform duration-200">
                    <span class="text-white font-bold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div
                    class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow-sm">
                </div>
            </div>

            <div class="ml-4 flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900 truncate tracking-tight">{{ auth()->user()->name }}</p>
                <div class="flex items-center mt-1">
                    <div class="w-2 h-2 rounded-full mr-2 bg-blue-500 animate-pulse">
                    </div>
                    <p class="text-xs font-medium capitalize text-blue-700">
                        {{ $userRole }}
                    </p>
                </div>
            </div>

            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </div>
        </div>
    </div>
</div>
