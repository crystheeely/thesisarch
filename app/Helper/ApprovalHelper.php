<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ApprovalHelper
{
    public static function ensureApproved()
    {
        $user = Auth::user();

        if ($user->role === 'student' && !$user->status === 'approved') {
            return redirect()->route('student.awaiting-approval');
        }

        return null;
    }
}
