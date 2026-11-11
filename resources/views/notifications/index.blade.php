@extends('v2.layout')
@section('content')
<div class="container" style="max-width:900px;margin:40px auto;">
<h2>Notifications</h2>
@forelse($notifications as $notification)
<a href="{{ route('notifications.open',$notification) }}" style="display:block;text-decoration:none;color:inherit;background:#fff;border:1px solid #f0dfe5;border-radius:14px;padding:16px;margin:12px 0;">
<strong>{{ $notification->title }}</strong><p style="margin:6px 0;">{{ $notification->message }}</p><small>{{ $notification->created_at->copy()->timezone('Asia/Yangon')->format('d M Y, h:i A') }}</small>
</a>
@empty<p>No notifications yet.</p>@endforelse
</div>
@endsection
