<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstagramFeed extends Model
{
    protected $fillable = ['embed_code', 'status', 'sort_order'];
}
