<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $category_id
 * @property string|null $image
 * @property string|null $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\GalleryCategory $category
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage query()
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryImage where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static GalleryImage create($attributes = [])
 * @method static GalleryImage|null find($id, $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 */
class GalleryImage extends Model
{
    protected $fillable = ['category_id', 'image', 'title'];

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }
}
