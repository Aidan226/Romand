<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>
@yield('title','Shade Studio · rom&nd')</title><link rel="stylesheet" href="{{ asset('css/studio.css') }}"></head><body>
<header class="topbar"><a class="wordmark" href="{{ route('home') }}">rom<span>&</span>nd <small>SHADE STUDIO</small></a><nav aria-label="Main navigation"><a href="{{ route('home') }}">Shop</a><a href="{{ route('cart.index') }}">Cart · {{ count(session('cart',[])) }}</a>
@auth<a href="{{ route('profile') }}">My account</a>
@if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}">Admin</a>
@endif<form method="post" action="{{ route('logout') }}">
@csrf<button class="text-button">Log out</button></form>
@else<a href="{{ route('login') }}">Sign in</a>
@endauth</nav></header>
<main class="studio">
@if(session('success'))<div class="notice" role="status">{{ session('success') }}</div>
@endif 
@if(session('error'))<div class="notice error">{{ session('error') }}</div>
@endif 
@if($errors->any())<div class="notice error" role="alert"><strong>Please check these details:</strong><ul>
@foreach($errors->all() as $e)<li>{{ $e }}</li>
@endforeach</ul></div>
@endif 
@yield('content')</main>
<footer class="studio-footer"><span>YOUR SHADE. YOUR WAY.</span><p>Student project inspired by rom&nd. Not an official brand store. Shade guidance is approximate; compare a physical swatch before purchasing cushion.</p><a href="{{ route('home') }}">Shop the collection →</a></footer>
@stack('scripts')</body></html>