<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
    @php
        $current = Route::currentRouteName();
        $isActive = str_starts_with($current, explode('.', $route)[0]);
    @endphp

    <li class="pc-item {{ $isActive ? 'active' : '' }}">
        <a href="{{ route($route) }}" class="pc-link">
            <span class="pc-micon"><i class="{{ $icon }}"></i></span>
            <span class="pc-mtext">{{ $title }}</span>
        </a>
    </li>
</div>
