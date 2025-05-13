<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['thesis_id', 'user_id', 'comment'];

    /**
     * A comment belongs to a thesis.
     */
    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }
    /**
     * A comment belongs to a user (admin).
     */
    public function user()
    {
        return $this->belongsTo(User::class); // assuming admins are users too
    }

    /**
     * Scope: Retrieve comments for a specific thesis.
     */
    public function scopeForThesis($query, $thesisId)
    {
        return $query->where('thesis_id', $thesisId)->orderBy('created_at', 'desc');
    }
}