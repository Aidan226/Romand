<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function paymentProof(Order $order)
    {
        abort_unless(auth()->user()->is_admin || $order->user_id === Auth::id(),403);
        $path = $order->payment_screenshot;
        abort_unless($path && str_starts_with($path,'payment-proofs/') && \Illuminate\Support\Facades\Storage::disk('local')->exists($path),404);
        return response()->file(\Illuminate\Support\Facades\Storage::disk('local')->path($path),['Cache-Control'=>'private, no-store']);
    }

    public function index()
    {
        $notifications = UserNotification::where('user_id', Auth::id())->latest()->get();
        UserNotification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return view('notifications.index', compact('notifications'));
    }

    public function open(UserNotification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->update(['is_read' => true]);
        if ($notification->order_id) {
            return redirect()->route('orders.show', $notification->order_id);
        }
        return redirect()->route('notifications.index');
    }

    public function order(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load('items');
        UserNotification::where('user_id', Auth::id())->where('order_id', $order->id)->update(['is_read' => true]);
        return view('orders.show', compact('order'));
    }
}
