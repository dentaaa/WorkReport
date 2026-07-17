<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function open(Notification $notification)
    {
        // pastikan hanya pemilik notification yang bisa membuka
        abort_if(
            $notification->user_id != Auth::id(),
            403
        );

        if (!$notification->is_read) {

            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return redirect($notification->action_url);
    }

    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    public function markAllRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'Semua notification berhasil ditandai sudah dibaca.'
        );
    }
}
