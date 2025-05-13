<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index'); // Make sure this Blade view exists
    }

    public function show($id)
    {
        return view('notifications.show', ['id' => $id]); // Dummy data
    }

    public function destroy($id)
    {
        // You can implement actual deletion logic here later
        return redirect()->route('notifications.index')->with('success', 'Notification deleted.');
    }
}
