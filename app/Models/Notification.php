<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Enable timestamps if your table has created_at and updated_at
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'title', 'message', 'is_read',
    ];

    // Optionally, cast is_read to boolean for easier use
    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Scope to get unread notifications
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Relationship to User model (if any)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
