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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\GalleryImage[] $images
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryCategory latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|GalleryCategory where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static GalleryCategory create($attributes = [])
 * @method static GalleryCategory|null find($id, $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 */
class GalleryCategory extends Model
{
    protected $fillable = ['name', 'slug', 'meta_title', 'meta_description'];

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'category_id');
    }
}
