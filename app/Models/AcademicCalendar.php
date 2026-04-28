<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $event_datetime
 * @property string|null $image
 * @property string|null $description
 * @property string|null $link_href
 * @property bool $status
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Institution $institution
 * 
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

class AcademicCalendar extends Model
{
    protected $fillable = [
        'title', 'slug', 'event_datetime', 'image', 'description', 'link_href', 'status',
        'meta_title', 'meta_description'
    ];

    protected $casts = [
        'event_datetime' => 'datetime',
        'status' => 'boolean',
    ];
}
