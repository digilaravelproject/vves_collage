<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'title',
        'image_path',
        'button_name',
        'button_link',
        'button_color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
