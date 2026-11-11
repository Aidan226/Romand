<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rom&nd · Student Shade Studio</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Navbar အတွက် Active State နှင့် ပန်းရောင်စတိုင်များ */
        nav ul li a.active {
            color: #d15c7a !important;
            font-weight: bold;
            border-bottom: 2px solid #d15c7a;
            padding-bottom: 4px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">rom&nd</div>
            <ul>
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home', 'product.show') ? 'active' : '' }}">Catalog</a></li>
                <li><a href="{{ route('cart.index') }}" class="{{ request()->routeIs('cart.*') ? 'active' : '' }}">Cart ({{ session('cart') ? count(session('cart')) : 0 }})</a></li>
                
                @guest
                    <li><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                    <li><a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Register</a></li>
                @else
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('admin.dashboard') }}" style="font-weight:bold;">Admin Dashboard</a></li>
                    @else
                        <li>
                            <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}" style="position:relative;">
                                🔔
                                @if(($unreadNotificationCount ?? 0) > 0)
                                    <span style="position:absolute;top:-8px;right:-10px;background:#d15c7a;color:white;border-radius:999px;min-width:18px;height:18px;padding:0 5px;font-size:11px;line-height:18px;text-align:center;">{{ $unreadNotificationCount }}</span>
                                @endif
                            </a>
                        </li>
                    @endif
                    <li><a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">👤 {{ Auth::user()->name }}</a></li>
                @endguest
            </ul>
        </nav>
    </header>
    @if(($activePromotion ?? null))
        <div style="background:#fdf2f4;border-bottom:1px solid #efcad5;color:#c34f70;text-align:center;padding:11px 15px;font-weight:600;">
            🎉 {{ $activePromotion->name }} — {{ rtrim(rtrim(number_format($activePromotion->effective_discount_percent,2), '0'), '.') }}% OFF
            @if($activePromotion->minimum_order > 0) on orders from {{ number_format($activePromotion->minimum_order) }} MMK @endif
            @if($activePromotion->ends_at) · Ends {{ $activePromotion->ends_at->format('d M Y') }} @endif
        </div>
    @endif
    <main>
        @if (session('success'))
            <div class="flash flash-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash-error">{{ session('error') }}</div>
        @endif
        @if($errors->any())<div class="flash flash-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        @yield('content')
    </main>
    <!-- Footer Section -->
    <footer class="store-footer">
        <div class="footer-container">
            
            <!-- Column 1: Quick Link -->
            <div class="footer-col">
                <h3>QUICK LINK</h3>
                <ul>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('how.to.buy') }}">How To Buy Guide?</a></li>
                </ul>
            </div>

            <!-- Column 2: Help -->
            <div class="footer-col">
                <h3>HELP</h3>
                <ul>
                    <li><a href="{{ route('payment') }}">Payment</a></li>
                    <li><a href="{{ route('returns.exchange') }}">Returns & Exchange</a></li>
                    <li><a href="{{ route('delivery') }}">Delivery</a></li>
                </ul>
            </div>

            <!-- Column 3: My Account -->
            <div class="footer-col">
                <h3>My Account</h3>
                <ul>
                    <li><a href="{{ route('profile') }}">My Personal Information</a></li>
                </ul>
            </div>

            <!-- Column 4: Customer Service -->
            <div class="footer-col">
                <h3>CUSTOMER SERVICE</h3>
                <ul>
                    <li>📧 service@romand-store.com</li>
                    <li>📞 09942176731</li>
                    <li>📞 09943768616</li>
                    <li>⏰ Monday to Sunday<br><span style="font-size: 13px; color: #aaa;">9:00 AM to 5:00 PM</span></li>
                </ul>
            </div>

        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 rom&nd · Student Shade Studio Store. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Footer အတွက် CSS စတိုင်များ -->
    <style>
        .store-footer {
            background-color: #1a1a1a;
            color: #fff;
            padding: 50px 20px 20px 20px;
            margin-top: 60px;
            font-family: inherit;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
        }

        .footer-col h3 {
            font-size: 15px;
            letter-spacing: 1px;
            margin-bottom: 20px;
            color: #fff;
            border-bottom: 2px solid #d15c7a;
            padding-bottom: 8px;
            display: inline-block;
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-col ul li {
            margin-bottom: 12px;
            font-size: 14px;
            color: #ccc;
            line-height: 1.5;
        }

        .footer-col ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-col ul li a:hover {
            color: #d15c7a;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 40px auto 0 auto;
            border-top: 1px solid #333;
            padding-top: 20px;
            text-align: center;
            font-size: 13px;
            color: #888;
        }
    </style>
</body>
</html>