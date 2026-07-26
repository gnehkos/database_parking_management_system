<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function clearAll()
    {
        Notification::where('is_read', 0)->update(['is_read' => 1]);
        return redirect()->route('notifications.index')->with('success', 'All notifications cleared.');
    }
}
