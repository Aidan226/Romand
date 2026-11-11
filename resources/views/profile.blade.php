@extends('v2.layout')

@section('content')
<style>
    .profile-container {
        display: flex;
        max-width: 1000px;
        margin: 40px auto;
        gap: 30px;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    
    .profile-sidebar {
        width: 250px;
        border-right: 1px solid #eaeaea;
        padding-right: 20px;
    }
    
    .profile-sidebar h3 {
        font-size: 14px;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    
    .profile-menu {
        list-style: none;
        padding: 0;
        margin: 0 0 30px 0;
    }
    
    .profile-menu li {
        margin-bottom: 10px;
    }
    
    /* Menu ခလုတ်များအတွက် ဒီဇိုင်း */
    .profile-menu button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        font-family: inherit;
        font-size: 15px;
        color: #444;
        font-weight: 500;
        display: block;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .profile-menu button:hover, .profile-menu button.active {
        background-color: #fdf2f4;
        color: #d15c7a;
        font-weight: 600;
    }
    
    .profile-content {
        flex: 1;
    }
    
    .profile-content h2 {
        margin-top: 0;
        border-bottom: 2px solid #f7cac9;
        padding-bottom: 10px;
        color: #333;
    }

    /* Tab အကန့်များကို အစပိုင်းမှာ ဖျောက်ထားရန် */
    .tab-section {
        display: none;
    }

    /* Active ဖြစ်နေသော Tab ကိုသာ ပြရန် */
    .tab-section.active-tab {
        display: block;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .order-table th, .order-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }
    
    .order-table th {
        background-color: #fafafa;
        color: #666;
        font-weight: 600;
    }

    .btn-logout {
        width: 100%;
        padding: 12px;
        background-color: #fff;
        color: #dc3545;
        border: 1px solid #dc3545;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    
    .btn-logout:hover {
        background-color: #dc3545;
        color: #fff;
    }

    @media(max-width: 768px) {
        .profile-container {
            flex-direction: column;
        }
        .profile-sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #eaeaea;
            padding-right: 0;
            padding-bottom: 20px;
        }
    }
</style>

<div class="profile-container">
    
    <!-- ==================== ဘယ်ဘက် Menu (Buttons) ==================== -->
    <aside class="profile-sidebar">
        <h3>General</h3>
        <ul class="profile-menu">
            <li><button type="button" class="tab-btn active" onclick="switchTab(event, 'profile-settings')">Profile Settings</button></li>
            <li><button type="button" class="tab-btn" onclick="switchTab(event, 'order-tracking')">Order Tracking</button></li>
            <li><button type="button" class="tab-btn" onclick="switchTab(event, 'customer-support')">Customer Support</button></li>
        </ul>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </aside>

    <!-- ==================== ညာဘက် Content (Tabs) ==================== -->
    <main class="profile-content">
        
        <!-- Tab 1: Profile Settings -->
        <div id="profile-settings" class="tab-section active-tab">
            <h2>Profile Settings</h2>
            <p style="color: #666; margin-top: 15px;"><strong>Name:</strong> {{ Auth::user()->name }}</p>
            <p style="color: #666;"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p style="color: #666;"><strong>Account Created:</strong> {{ Auth::user()->created_at->format('Y-m-d') }}</p>
        </div>

        <!-- Tab 2: Order Tracking -->
        <div id="order-tracking" class="tab-section">
            <h2>Order Tracking & History</h2>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Product Name</th>
                        <th>Shade</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>#ORD-{{ $order->id }}</td>
                            <td>
                                {{ $order->created_at->format('Y-m-d') }}
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                {{ $item->product_name }}
                                @if(!$loop->last)
                                    <br>
                                @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($order->items as $item)
                                {{ $item->shade }}
                                @if(!$loop->last)
                                    <br>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ number_format($order->total_price) }} MMK
                            </td>
                            <td>
                                {{ ucfirst($order->status) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tab 3: Customer Support -->
        <div id="customer-support" class="tab-section">
            <h2>Customer Support</h2>
            <div style="background: #fafafa; padding: 25px; border-radius: 8px; border: 1px solid #eaeaea; margin-top: 20px;">
                <h3 style="color: #333; font-size: 18px; margin-top: 0; margin-bottom: 10px;">💬 Need Help?</h3>
                <p style="color: #666; font-size: 15px; margin-bottom: 15px;">If you have any questions regarding your order, shipping, or product details, feel free to contact our support team anytime.</p>
                <p style="color: #d15c7a; font-weight: 600; margin: 0; font-size: 16px;">Email: support@romand-store.com</p>
                <p style="color: #d15c7a; font-weight: 600; margin-top: 5px; font-size: 16px;">Phone: 09-123456789 (MM)</p>
            </div>
        </div>

    </main>

</div>

<!-- Tab များကို ပြောင်းလဲပေးမည့် JavaScript -->
<script>
function switchTab(evt, tabName) {
    // Tab အားလုံးကို ဖျောက်မည်
    let sections = document.getElementsByClassName("tab-section");
    for (let i = 0; i < sections.length; i++) {
        sections[i].classList.remove("active-tab");
    }

    // Menu ခလုတ်များအားလုံးမှ active class ကို ဖြုတ်မည်
    let buttons = document.getElementsByClassName("tab-btn");
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("active");
    }

    // ရွေးလိုက်သော Tab ကို ပေါ်လာစေပြီး ခလုတ်ကို active လုပ်မည်
    document.getElementById(tabName).classList.add("active-tab");
    evt.currentTarget.classList.add("active");
}
</script>
@endsection