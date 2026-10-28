<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a specific notification as read and redirect to target URL.
     */
    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = Auth::user()->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $targetUrl = $notification->data['url'] ?? route('products.low-stock');

        return redirect($targetUrl);
    }

    /**
     * Mark all unread notifications for the authenticated user as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }
}
