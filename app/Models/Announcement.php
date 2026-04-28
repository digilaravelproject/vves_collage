<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $type
 * @property bool $status
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin Illuminate\Database\Eloquent\Model
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'status',
        'meta_title',
        'meta_description',
        'link',
    ];
}
