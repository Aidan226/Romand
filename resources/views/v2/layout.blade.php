<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Shade Studio · rom&nd')</title>
    <link rel="stylesheet" href="{{ asset('css/studio.css') }}">
    @stack('styles')
</head>
<body>

@if(($activePromotion ?? null))
    <div class="topbar-promo">
        🎉 {{ $activePromotion->name }} — {{ rtrim(rtrim(number_format($activePromotion->effective_discount_percent, 2), '0'), '.') }}% OFF
        @if($activePromotion->minimum_order > 0) on orders from {{ number_format($activePromotion->minimum_order) }} MMK @endif
        @if($activePromotion->ends_at) · Ends {{ $activePromotion->ends_at->format('d M Y') }} @endif
    </div>
@endif

<header class="topbar">
    <div class="topbar-inner">
        <div class="topbar-left">
            <a class="wordmark" href="{{ route('home') }}">
                rom<span>&</span>nd <small>SHADE STUDIO</small>
            </a>
            <nav class="nav-links-left" aria-label="Main Store Navigation">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home', 'product.show', 'category.show') ? 'active-link' : '' }}">Shop</a>
                <a href="{{ route('shade.finder') }}" class="{{ request()->routeIs('shade.finder', 'shade.analyze') ? 'active-link' : '' }}">Shade Finder</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active-link' : '' }}">About</a>
            </nav>
        </div>

        <div class="nav-actions-cluster">
            @auth
                <!-- Notification Bell Icon with Badge -->
                <a href="{{ route('notifications.index') }}" class="nav-action-icon {{ request()->routeIs('notifications.*') ? 'active' : '' }}" title="Notifications" aria-label="Notifications">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    @if(($unreadNotificationCount ?? 0) > 0)
                        <span class="nav-counter-badge">{{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}</span>
                    @endif
                </a>

                <!-- Shopping Bag Icon with Badge -->
                <a href="{{ route('cart.index') }}" class="nav-action-icon {{ request()->routeIs('cart.*') ? 'active' : '' }}" title="Shopping Cart" aria-label="Cart">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="nav-counter-badge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>

                <!-- User Account Dropdown -->
                <div class="nav-user-dropdown-wrap" id="user-dropdown-wrap">
                    <button type="button" class="nav-user-btn" id="user-dropdown-trigger" aria-expanded="false" aria-haspopup="true">
                        <span class="nav-avatar-circle">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                            @else
                                <span>{{ auth()->user()->initials }}</span>
                            @endif
                        </span>
                        <span class="nav-user-name">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        <svg class="dropdown-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="nav-user-menu" id="user-dropdown-menu">
                        <div class="user-menu-header">
                            <div class="user-menu-avatar">
                                @if(auth()->user()->avatar_url)
                                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                                @else
                                    <span>{{ auth()->user()->initials }}</span>
                                @endif
                            </div>
                            <div class="user-menu-info">
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ auth()->user()->email }}</small>
                            </div>
                        </div>

                        <div class="user-menu-divider"></div>

                        <a href="{{ route('profile') }}" class="user-menu-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Profile Settings</span>
                        </a>

                        <a href="{{ route('profile') }}#orders" class="user-menu-item" onclick="if(window.switchTab){switchTab(event, 'order-tracking');}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m7.5 4.27 9 5.15"></path>
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                                <path d="m3.3 7 8.7 5 8.7-5"></path>
                                <path d="M12 22V12"></path>
                            </svg>
                            <span>My Orders</span>
                        </a>

                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="user-menu-item admin-highlight">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                                <span>Admin Dashboard</span>
                            </a>
                        @endif

                        <div class="user-menu-divider"></div>

                        <form method="post" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="user-menu-item logout-link" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer; font-family: inherit;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Log out</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Guest Actions -->
                <a href="{{ route('cart.index') }}" class="nav-action-icon {{ request()->routeIs('cart.*') ? 'active' : '' }}" title="Shopping Cart" aria-label="Cart">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span class="nav-counter-badge">{{ $cartCount > 9 ? '9+' : $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('login') }}" class="nav-text-btn">Sign in</a>
                <a href="{{ route('register') }}" class="nav-solid-btn">Register</a>
            @endauth
        </div>
    </div>
</header>

<main class="studio">
    @if(session('success'))
        <div class="notice" role="status">{{ session('success') }}</div>
    @endif 
    @if(session('error'))
        <div class="notice error">{{ session('error') }}</div>
    @endif 
    @if($errors->any())
        <div class="notice error" role="alert">
            <strong>Please check these details:</strong>
            <ul style="margin: 8px 0 0 18px; padding: 0;">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif 
    @yield('content')
</main>

<footer class="studio-footer">
    <span>YOUR SHADE. YOUR WAY.</span>
    <p>Student project inspired by rom&nd. Not an official brand store. Shade guidance is approximate; compare a physical swatch before purchasing cushion.</p>
    <a href="{{ route('home') }}">Shop the collection →</a>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trigger = document.getElementById('user-dropdown-trigger');
    const wrap = document.getElementById('user-dropdown-wrap');
    if (trigger && wrap) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            wrap.classList.toggle('open');
            trigger.setAttribute('aria-expanded', wrap.classList.contains('open'));
        });
        document.addEventListener('click', function(e) {
            if (!wrap.contains(e.target)) {
                wrap.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>

@stack('scripts')
</body>
</html>
