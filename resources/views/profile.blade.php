@extends('v2.layout')

@section('title', 'My Profile · Shade Studio')

@section('content')
<style>
    .profile-container {
        display: flex;
        max-width: 1060px;
        margin: 30px auto;
        gap: 32px;
        background: #ffffff;
        padding: 32px;
        border-radius: 14px;
        border: 1px solid #ebd9d2;
        box-shadow: 0 4px 20px rgba(57, 40, 45, 0.05);
    }
    
    .profile-sidebar {
        width: 270px;
        border-right: 1px solid #f0e4df;
        padding-right: 24px;
        display: flex;
        flex-direction: column;
    }

    .profile-user-summary {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f2e7e3;
    }

    .profile-avatar-thumb {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        overflow: hidden;
        background: #f7ebe7;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        color: var(--rose);
        border: 2px solid #ebd9d2;
        flex-shrink: 0;
    }

    .profile-avatar-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-user-meta {
        min-width: 0;
    }

    .profile-user-meta strong {
        display: block;
        font-size: 15px;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .profile-user-meta small {
        display: block;
        color: var(--muted);
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .profile-sidebar h3 {
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 0 0 14px 4px;
    }
    
    .profile-menu {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
    }
    
    .profile-menu li {
        margin-bottom: 8px;
    }
    
    .profile-menu button {
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        font-family: inherit;
        font-size: 14px;
        color: var(--ink);
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .profile-menu button:hover, .profile-menu button.active {
        background-color: #fdf2f4;
        color: var(--rose);
        font-weight: 600;
    }
    
    .profile-content {
        flex: 1;
        min-width: 0;
    }
    
    .profile-content h2 {
        margin-top: 0;
        font-size: 24px;
        border-bottom: 2px solid #fae2e8;
        padding-bottom: 12px;
        color: var(--ink);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tab-section {
        display: none;
    }

    .tab-section.active-tab {
        display: block;
        animation: fadeIn 0.25s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Avatar Edit Block */
    .avatar-edit-card {
        display: flex;
        align-items: center;
        gap: 24px;
        background: #fdfaf9;
        border: 1px solid #ebdcd7;
        border-radius: 12px;
        padding: 20px 24px;
        margin: 20px 0 26px;
    }

    .avatar-preview-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        background: #f5e7e3;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #ebd5ce;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }

    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-large-initials {
        font-size: 36px;
        font-weight: 700;
        color: var(--rose);
        user-select: none;
    }

    .avatar-meta-col {
        flex: 1;
    }

    .avatar-meta-col h4 {
        margin: 0 0 6px;
        font-size: 16px;
        color: var(--ink);
    }

    .avatar-meta-col p {
        margin: 0 0 14px;
        font-size: 13px;
        color: var(--muted);
    }

    .avatar-actions-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-pick-avatar {
        background: #39282d;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-pick-avatar:hover {
        background: var(--rose);
    }

    .btn-remove-avatar {
        background: transparent;
        color: #c94a5e;
        border: 1px solid #ebd0d5;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-remove-avatar:hover {
        background: #fff0f2;
        border-color: #c94a5e;
    }

    /* Form Fields */
    .profile-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .profile-form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .profile-form-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink);
    }

    .profile-form-group input {
        padding: 11px 14px;
        border: 1px solid #d8c8c3;
        border-radius: 6px;
        font-size: 14px;
        color: var(--ink);
        background: #ffffff;
        transition: border-color 0.2s ease;
    }

    .profile-form-group input:focus {
        border-color: var(--rose);
        outline: none;
        box-shadow: 0 0 0 3px rgba(154, 69, 92, 0.12);
    }

    .field-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 4px;
    }

    .profile-footer-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid #f0e4df;
        gap: 16px;
        flex-wrap: wrap;
    }

    .profile-member-badge {
        font-size: 13px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save-profile {
        background: var(--ink);
        color: #ffffff;
        border: none;
        padding: 12px 26px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-save-profile:hover {
        background: var(--rose);
    }

    /* Order Tracking Table */
    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
        font-size: 13px;
    }
    
    .order-table th, .order-table td {
        padding: 14px 12px;
        text-align: left;
        border-bottom: 1px solid #f2e7e3;
    }
    
    .order-table th {
        background-color: #faf4f2;
        color: var(--ink);
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .order-status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-processing, .status-paid {
        background: #e7f7ed;
        color: #1e7e34;
    }

    .status-pending, .status-under-review {
        background: #fff8e6;
        color: #b27a00;
    }

    .status-cancelled, .status-failed {
        background: #feecef;
        color: #c94a5e;
    }

    .btn-logout {
        margin-top: auto;
        width: 100%;
        padding: 11px;
        background-color: #ffffff;
        color: #dc3545;
        border: 1px solid #ebd0d5;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-logout:hover {
        background-color: #dc3545;
        color: #ffffff;
        border-color: #dc3545;
    }

    @media(max-width: 860px) {
        .profile-container {
            flex-direction: column;
            padding: 20px;
            gap: 24px;
        }
        .profile-sidebar {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #f0e4df;
            padding-right: 0;
            padding-bottom: 20px;
        }
        .profile-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .avatar-edit-card {
            flex-direction: column;
            text-align: center;
        }
        .avatar-actions-row {
            justify-content: center;
        }
    }
</style>

<div class="profile-container">
    
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <div class="profile-user-summary">
            <div class="profile-avatar-thumb">
                @if(Auth::user()->avatar_url)
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}">
                @else
                    <span>{{ Auth::user()->initials }}</span>
                @endif
            </div>
            <div class="profile-user-meta">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
            </div>
        </div>

        <h3>Menu</h3>
        <ul class="profile-menu">
            <li><button type="button" class="tab-btn active" onclick="switchTab(event, 'profile-settings')">⚙️ Profile Settings</button></li>
            <li><button type="button" class="tab-btn" onclick="switchTab(event, 'order-tracking')">📦 Order Tracking ({{ $orders->count() }})</button></li>
            <li><button type="button" class="tab-btn" onclick="switchTab(event, 'customer-support')">💬 Customer Support</button></li>
        </ul>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Log out</button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="profile-content">
        
        <!-- Tab 1: Profile Settings -->
        <div id="profile-settings" class="tab-section active-tab">
            <h2>Profile Settings</h2>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-update-form">
                @csrf
                
                <div class="avatar-edit-card">
                    <div class="avatar-preview-wrap">
                        <div class="avatar-preview" id="avatar-preview-box">
                            @if(Auth::user()->avatar_url)
                                <img id="avatar-img-preview" src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}">
                                <span id="avatar-initials-preview" class="avatar-large-initials" style="display:none;">{{ Auth::user()->initials }}</span>
                            @else
                                <img id="avatar-img-preview" src="" alt="{{ Auth::user()->name }}" style="display:none;">
                                <span id="avatar-initials-preview" class="avatar-large-initials">{{ Auth::user()->initials }}</span>
                            @endif
                        </div>
                        <input type="file" id="avatar-file-input" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" style="display:none;">
                        <input type="hidden" name="remove_avatar" id="remove-avatar-input" value="0">
                    </div>

                    <div class="avatar-meta-col">
                        <h4>Profile Photo</h4>
                        <p>Upload a friendly photo for your Shade Studio account. JPG, PNG or WEBP up to 2MB.</p>
                        <div class="avatar-actions-row">
                            <button type="button" class="btn-pick-avatar" onclick="document.getElementById('avatar-file-input').click()">
                                📷 Choose Photo
                            </button>
                            @if(Auth::user()->avatar)
                                <button type="button" class="btn-remove-avatar" id="btn-remove-photo" onclick="handleRemoveAvatar()">
                                    Remove Photo
                                </button>
                            @else
                                <button type="button" class="btn-remove-avatar" id="btn-remove-photo" onclick="handleRemoveAvatar()" style="display:none;">
                                    Remove Photo
                                </button>
                            @endif
                        </div>
                        @error('avatar')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="profile-form-grid">
                    <div class="profile-form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="profile-form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="profile-footer-row">
                    <div class="profile-member-badge">
                        <span>🗓️ Member since {{ Auth::user()->created_at->format('M d, Y') }}</span>
                        <span>•</span>
                        <span>🛍️ {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }} placed</span>
                    </div>
                    <button type="submit" class="btn-save-profile">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Tab 2: Order Tracking -->
        <div id="order-tracking" class="tab-section">
            <h2>Order Tracking & History</h2>
            @if($orders->isEmpty())
                <div style="text-align: center; padding: 40px 20px; color: var(--muted);">
                    <p style="font-size: 16px; margin-bottom: 12px;">You haven't placed any orders yet.</p>
                    <a href="{{ route('home') }}" class="button" style="padding: 9px 18px; font-size: 13px;">Start Shopping →</a>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Shade</th>
                                <th>Total</th>
                                <th>Payment / Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td style="font-weight: 600;">#ORD-{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @foreach($order->items as $item)
                                            <div>{{ $item->product_name }} <small style="color: var(--muted);">x{{ $item->quantity ?? 1 }}</small></div>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($order->items as $item)
                                            <div>{{ $item->shade ?: '—' }}</div>
                                        @endforeach
                                    </td>
                                    <td style="font-weight: 600;">{{ number_format($order->total_price) }} MMK</td>
                                    <td>
                                        @php
                                            $statusClass = match(strtolower($order->status)) {
                                                'paid', 'completed' => 'status-paid',
                                                'processing' => 'status-processing',
                                                'cancelled', 'rejected' => 'status-cancelled',
                                                default => 'status-pending',
                                            };
                                        @endphp
                                        <span class="order-status-badge {{ $statusClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" style="color: var(--rose); font-weight: 600; text-decoration: underline; font-size: 12px;">
                                            Details →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Tab 3: Customer Support -->
        <div id="customer-support" class="tab-section">
            <h2>Customer Support</h2>
            <div style="background: #faf4f2; padding: 24px; border-radius: 10px; border: 1px solid #ebdcd7; margin-top: 18px;">
                <h3 style="color: var(--ink); font-size: 17px; margin-top: 0; margin-bottom: 8px;">💬 We're Here to Help</h3>
                <p style="color: var(--muted); font-size: 14px; margin-bottom: 16px; line-height: 1.5;">
                    If you have questions regarding your order status, shade matching, or KPay verification, reach out to our team directly:
                </p>
                <p style="color: var(--rose); font-weight: 600; margin: 0 0 6px; font-size: 14px;">📧 Email: support@romand-store.com</p>
                <p style="color: var(--rose); font-weight: 600; margin: 0; font-size: 14px;">📞 Phone: 09-123456789 / 09-942176731</p>
            </div>
        </div>

    </main>
</div>

@push('scripts')
<script>
function switchTab(evt, tabName) {
    let sections = document.getElementsByClassName("tab-section");
    for (let i = 0; i < sections.length; i++) {
        sections[i].classList.remove("active-tab");
    }

    let buttons = document.getElementsByClassName("tab-btn");
    for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove("active");
    }

    const targetSection = document.getElementById(tabName);
    if (targetSection) {
        targetSection.classList.add("active-tab");
    }
    if (evt && evt.currentTarget) {
        evt.currentTarget.classList.add("active");
    }
}

window.addEventListener('DOMContentLoaded', function() {
    if (window.location.hash === '#orders') {
        const orderTabBtn = document.querySelectorAll('.tab-btn')[1];
        if (orderTabBtn) {
            switchTab({ currentTarget: orderTabBtn }, 'order-tracking');
        }
    }
});

const avatarFileInput = document.getElementById('avatar-file-input');
const avatarImgPreview = document.getElementById('avatar-img-preview');
const avatarInitialsPreview = document.getElementById('avatar-initials-preview');
const removeAvatarInput = document.getElementById('remove-avatar-input');
const btnRemovePhoto = document.getElementById('btn-remove-photo');

if (avatarFileInput) {
    avatarFileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('File size exceeds 2MB limit. Please select a smaller photo.');
            avatarFileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            avatarImgPreview.src = event.target.result;
            avatarImgPreview.style.display = 'block';
            if (avatarInitialsPreview) {
                avatarInitialsPreview.style.display = 'none';
            }
            if (removeAvatarInput) {
                removeAvatarInput.value = '0';
            }
            if (btnRemovePhoto) {
                btnRemovePhoto.style.display = 'inline-block';
            }
        };
        reader.readAsDataURL(file);
    });
}

function handleRemoveAvatar() {
    if (confirm('Remove your profile photo and revert to initials?')) {
        if (avatarFileInput) avatarFileInput.value = '';
        if (removeAvatarInput) removeAvatarInput.value = '1';
        if (avatarImgPreview) {
            avatarImgPreview.src = '';
            avatarImgPreview.style.display = 'none';
        }
        if (avatarInitialsPreview) {
            avatarInitialsPreview.style.display = 'block';
        }
        if (btnRemovePhoto) {
            btnRemovePhoto.style.display = 'none';
        }
    }
}
</script>
@endpush
@endsection
