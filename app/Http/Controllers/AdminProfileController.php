<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function show()
{
    $user = Auth::user();

    if ($user->role !== 'admin') {
        abort(403, 'Unauthorized access.');
    }

    return view('admin.profile');
}

}
