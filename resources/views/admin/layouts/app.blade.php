<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - rom&nd Store</title>
     

    <style>
        *{box-sizing:border-box} :root{--pink:#d95b7f;--pink2:#f7c9d7;--soft:#fff7fa;--ink:#382f33;--muted:#86777d;--line:#f0dfe5;--white:#fff;--danger:#c94c58}
        body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:linear-gradient(180deg,#fff9fb 0,#f8f5f7 100%);color:var(--ink)}
        .wrap{display:flex;min-height:100vh}.sidebar{width:258px;background:#fff;border-right:1px solid var(--line);padding:26px 18px;position:fixed;inset:0 auto 0 0;overflow:auto;box-shadow:8px 0 30px rgba(119,68,85,.04)}
        .brand{font-size:27px;font-weight:900;color:var(--pink);margin:0 10px 7px;letter-spacing:-1px}.brand small{display:block;font-size:11px;font-weight:700;letter-spacing:2px;color:#aa929b;margin-top:6px;text-transform:uppercase}.nav-title{font-size:10px;letter-spacing:1.5px;color:#b39ca5;margin:24px 11px 8px;text-transform:uppercase;font-weight:800}
        .sidebar a{display:flex;align-items:center;gap:10px;color:#594a50;text-decoration:none;padding:12px 13px;border-radius:12px;margin:4px 0;font-weight:650;font-size:14px}.sidebar a:hover,.sidebar a.active{background:#fff0f5;color:var(--pink)}.nav-dot{width:8px;height:8px;border-radius:50%;background:#e7c6d1}.sidebar a.active .nav-dot{background:var(--pink)}
        .main{margin-left:258px;width:calc(100% - 258px);padding:34px;max-width:1600px}.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:26px}.top h1{margin:0;font-size:31px;letter-spacing:-.7px}.admin-pill{background:white;border:1px solid var(--line);padding:10px 14px;border-radius:999px;color:#74636a;font-size:13px;box-shadow:0 5px 16px rgba(112,67,82,.05)}
        .card{background:rgba(255,255,255,.96);border:1px solid var(--line);border-radius:18px;padding:20px;box-shadow:0 9px 26px rgba(112,67,82,.06)}.grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.stat{position:relative;overflow:hidden}.stat:after{content:"";position:absolute;width:70px;height:70px;border-radius:50%;right:-18px;top:-18px;background:#fff0f5}.stat span{color:var(--muted);font-size:13px;font-weight:700}.stat b{font-size:32px;display:block;margin-top:9px;color:#3b3034}.section{margin-top:22px}
        table{width:100%;border-collapse:collapse;background:#fff}th,td{padding:13px 12px;border-bottom:1px solid #f4e9ed;text-align:left;vertical-align:middle}th{background:#fff7fa;color:#806a72;font-size:12px;text-transform:uppercase;letter-spacing:.4px}.table-wrap{overflow:auto;border-radius:18px;border:1px solid var(--line);box-shadow:0 8px 24px rgba(112,67,82,.05)}
        input,select,textarea{width:100%;padding:10px 11px;border:1px solid #e5d6dc;border-radius:10px;background:#fff;color:#44383d;outline:none}input:focus,select:focus,textarea:focus{border-color:#df91aa;box-shadow:0 0 0 3px #fff0f5}.btn{border:0;border-radius:10px;padding:10px 14px;cursor:pointer;text-decoration:none;display:inline-block;font-weight:700}.primary{background:var(--pink);color:#fff}.primary:hover{filter:brightness(.96)}.danger{background:#fff0f1;color:var(--danger);border:1px solid #f3cfd4}.muted{background:#f7f2f4;color:#65555c}.alert{padding:13px 15px;border-radius:12px;margin-bottom:16px}.success{background:#edf8f0;color:#2d7241}.error{background:#fff0f1;color:#9d353e}.form-grid{display:grid;grid-template-columns:1.6fr 1.2fr .8fr .6fr 1.4fr auto;gap:11px;align-items:end}.badge{padding:5px 9px;border-radius:999px;background:#fff0f5;color:#bb4f6e;font-size:12px;font-weight:700;white-space:nowrap}.actions{display:flex;gap:7px;flex-wrap:wrap}.small{font-size:12px;color:#8c7a81}.low{color:#c94c58;font-weight:800}.dash-grid{display:grid;grid-template-columns:minmax(0,2fr) minmax(260px,1fr);gap:18px}.card h3{margin-top:0}.product-thumb{width:54px;height:54px;object-fit:cover;border-radius:10px;border:1px solid var(--line)}
        @media(max-width:1050px){.grid{grid-template-columns:repeat(2,1fr)}.form-grid{grid-template-columns:1fr 1fr}.dash-grid{grid-template-columns:1fr}}
        @media(max-width:720px){.sidebar{position:static;width:100%}.wrap{display:block}.main{margin:0;width:100%;padding:18px}.grid,.form-grid{grid-template-columns:1fr}.top{align-items:flex-start;gap:12px;flex-direction:column}}
    
    /* Shade Studio style for the admin dashboard */
:root {
    --pink: #9a455c;
    --pink2: #e5c8cc;
    --soft: #fbf7f4;
    --ink: #39282d;
    --muted: #806e73;
    --line: #e8dcd8;
}

body {
    background: #fbf7f4;
    color: var(--ink);
    font: 15px/1.6 system-ui, sans-serif;
}

button, input, select, textarea {
    font: inherit;
}

/* Sidebar */
.sidebar {
    background: #fffcf9;
    border-right: 1px solid var(--line);
    box-shadow: none;
    padding-top: 32px;
}

.brand {
    color: var(--ink);
    font-size: 30px;
    font-weight: 800;
    letter-spacing: -2px;
}

.brand small {
    color: var(--muted);
    font-size: 9px;
    font-weight: 500;
    letter-spacing: 3px;
}

.nav-title {
    color: var(--muted);
    font-size: 10px;
    letter-spacing: 2px;
}

.sidebar a {
    font-size: 13px;
    font-weight: 500;
    border-radius: 5px;
}

.sidebar a:hover {
    background: #f4e9e3;
}

.sidebar a.active {
    background: #efe2dc;
    color: var(--pink);
}

.nav-dot {
    background: #d9b7bd;
}

/* Page heading */
.main {
    padding: 40px;
    max-width: none;
}

.top {
    padding-bottom: 24px;
    border-bottom: 1px solid var(--line);
}

.top h1 {
    font: 46px/1.1 Georgia, serif;
    letter-spacing: -1.5px;
}

.admin-pill {
    background: #fffcf9;
    border-color: var(--line);
    box-shadow: none;
}

/* Summary cards */
.card {
    background: #fffdfb;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 24px;
    box-shadow: none;
}

.stat b {
    color: var(--ink);
    font: 42px/1.2 Georgia, serif;
    margin: 14px 0 4px;
}

.stat span {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 1px;
}

.stat .small {
    font-size: 12px;
    letter-spacing: 0;
}

.stat::after {
    background: #f2e5df;
}

.card h3 {
    font: 26px/1.2 Georgia, serif;
    letter-spacing: -0.5px;
}

/* Orders table */
table {
    background: transparent;
}

th {
    background: #f6eeea;
    color: var(--muted);
    font-size: 10px;
    letter-spacing: 1px;
}

td {
    font-size: 13px;
    padding-top: 18px;
    padding-bottom: 18px;
}

th, td {
    border-bottom-color: var(--line);
}

.badge {
    background: #f1e5e3;
    color: var(--pink);
    font-size: 11px;
    font-weight: 500;
}

/* Buttons */
.btn {
    border-radius: 4px;
    padding: 10px 15px;
    font-size: 12px;
    font-weight: 500;
}

.btn.primary {
    background: var(--ink);
    color: white;
}

.btn.muted {
    background: #f1e7e3;
    color: var(--ink);
}

/* Inputs */
input, select, textarea {
    border-radius: 4px;
    border-color: #d8c8c3;
}

input:focus, select:focus, textarea:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 3px #f2e5df;
}

/* Tablet and mobile */
@media (max-width: 1100px) {
    .main {
        padding: 26px;
    }

    .grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dash-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 720px) {
    .main {
        margin: 0;
        width: 100%;
        padding: 22px 16px;
    }

    .top h1 {
        font-size: 36px;
    }

    .grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .stat {
        padding: 16px;
    }

    .stat b {
        font-size: 34px;
    }
}
    </style>

</head>
<body>
<div class="wrap">
    <aside class="sidebar">
        <div class="brand">rom&nd<small>Store Administration</small></div>
        <div class="nav-title">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="nav-dot"></span>Dashboard</a>
        <div class="nav-title">Store Management</div>
        <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}"><span class="nav-dot"></span>Products & Stock</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><span class="nav-dot"></span>Orders</a>
        <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}"><span class="nav-dot"></span>Discounts & Promotions</a>
        <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"><span class="nav-dot"></span>Customers</a>
        <div class="nav-title">Website</div>
        <a href="{{ route('home') }}"><span class="nav-dot"></span>View Customer Store</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:20px;">@csrf<button type="submit" class="btn muted" style="width:100%;">Logout</button></form>
    </aside>
    <main class="main">
        <div class="top"><h1>@yield('title','Admin Dashboard')</h1><div class="admin-pill">User - Admin</div></div>
        @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert error"><ul style="margin:0;padding-left:20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if($errors->any())<div class="card"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif @yield('content')
    </main>
</div>
@stack('scripts')
</body>
</html>
