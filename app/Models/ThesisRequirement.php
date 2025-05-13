<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'title',
        'file_path',
        'original_filename',
    ];
}
