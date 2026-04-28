<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $event_date
 * @property string|null $venue
 * @property string|null $link
 * @property string|null $short_description
 * @property string|null $full_content
 * @property string $status
 * @property int $preference_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\EventCategory $category
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|EventItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventItem latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|EventItem where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static EventItem create($attributes = [])
 * @method static EventItem|null find($id, $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 */
class EventItem extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'image',
        'event_date',
        'venue',
        'link', // <--- Added here
        'short_description',
        'full_content',
        'status',
        'preference_order',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }
}
