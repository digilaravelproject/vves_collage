<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EventItem[] $items
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static EventCategory create($attributes = [])
 * @method static EventCategory|null find($id, $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 */
class EventCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'meta_title', 'meta_description'
    ];

    public function items()
    {
        return $this->hasMany(EventItem::class, 'category_id');
    }
}
