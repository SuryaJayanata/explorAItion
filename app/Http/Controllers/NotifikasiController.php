<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifications = Notifikasi::where('id_user', Auth::id())
            ->latest()
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notifikasi::where('id_user', Auth::id())
            ->findOrFail($id);
        
        $notification->update(['dibaca' => true]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->notifications()->unread()->count()
            ]);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        Notifikasi::where('id_user', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'unread_count' => 0
            ]);
        }

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function getUnreadCount()
    {
        $count = Notifikasi::where('id_user', Auth::id())
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getUnreadNotifications()
    {
        $notifications = Notifikasi::where('id_user', Auth::id())
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }
}