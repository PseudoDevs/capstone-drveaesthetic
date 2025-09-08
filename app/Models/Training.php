<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'thumbnail',
        'description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
