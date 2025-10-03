@props(['roles' => [], 'user' => null])

@php
    $currentUser = $user ?? auth()->user();
    $userRole = $currentUser->role ?? null;
    $hasAccess = empty($roles) || in_array($userRole, $roles);
@endphp

@if ($hasAccess)
    {{ $slot }}
@endif

<!-- Usage in any blade template: -->
{{--
<x-role-check :roles="['owner', 'staff']">
    <button>Only owners and staff can see this</button>
</x-role-check>

<x-role-check :roles="['tenant']">
    <div>Only tenants can see this</div>
</x-role-check>
--}}
