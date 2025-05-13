<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REVISED = 'Revised';


    public function user()
    {
        return $this->belongsTo(User::class);
    }    

    protected $fillable = [
        'user_id', 
        'title', 
        'full_name',
        'abstract', 
        'author_name', 
        'academic_year', 
        'semester', 
        'month', 
        'coauthors', 
        'file_path', 
        'status', 
        'keywords'];

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
    
    public function requirements()
    {
        return $this->hasMany(ThesisRequirement::class);
    }

    protected $casts = [
        'coauthors' => 'array',
    ];
    
    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
    
}