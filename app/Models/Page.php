<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Page
 *
 * @package App\Models
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property mixed $content
 * @property string|null $image
 * @property string|null $pdf
 * @property string|null $breadcrumb_image
 * @property string|null $breadcrumb_note
 * @property int|null $menu_id
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Menu|null $menu
 * @property-read string $link
 * @property-read array $sections
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin Illuminate\Database\Eloquent\Model
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'image',
        'pdf',
        'menu_id',
        'status',
        'breadcrumb_image',
        'breadcrumb_note',
    ];

    protected $casts = [
        // 'content' => 'json',
        'status' => 'boolean',
    ];

    protected $appends = ['sections'];

    /**
     * Use slug for route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Linked menu item (optional).
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Ensure slug is always lowercase, trimmed, and prefixed.
     */
    public function setSlugAttribute(string $value): void
    {
        $this->attributes['slug'] = strtolower(trim($value, '/'));
    }


    /**
     * Ensure title is properly capitalized.
     */
    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = ucwords(trim($value));
    }

    /**
     * Generate the public URL for this page.
     */
    public function getLinkAttribute(): string
    {
        return route('page.view', $this->slug);
    }

    /**
     * Parse section blocks from JSON content.
     */
    public function getSectionsAttribute(): array
    {
        return $this->getSections();
    }

    /**
     * Parse section blocks from JSON content.
     */
    public function getSections(): array
    {
        if (empty($this->content)) {
            return [];
        }

        $content = is_string($this->content) ? json_decode($this->content, true) : $this->content;

        if (!is_array($content)) {
            return [];
        }

        $sections = [];
        foreach ($content as $block) {
            if (isset($block['type']) && $block['type'] === 'section') {
                $id = $block['id'] ?? null;
                if ($id) {
                    $title = $block['data']['title'] ?? $id;
                    $sections[] = [
                        'id' => 'section-' . $id,
                        'title' => $title,
                    ];
                }
            }
        }

        return $sections;
    }
}
