<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Menu
 *
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string|null $url
 * @property int|null $parent_id
 * @property int $order
 * @property bool $status
 * @property \App\Models\Page|null $page
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Menu[] $children
 * @property-read string $link
 * @property-read string $statusLabel
 */
class Menu extends Model
{
    protected $fillable = [
        'title',
        'url',
        'page_id',
        'section_id',
        'parent_id',
        'order',
        'status',
    ];

    /**
     * ============================
     * ACCESSORS & MUTATORS
     * ============================
     */

    public function getTitleAttribute(?string $value): string
    {
        return ucwords($value ?? '');
    }

    public function setTitleAttribute(string $value): void
    {
        $this->attributes['title'] = preg_replace('/\s+/', ' ', trim($value));
    }

    /**
     * ============================
     * RELATIONSHIPS
     * ============================
     */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('status', true)
            ->orderBy('order');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * ============================
     * SCOPES
     * ============================
     */

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * ============================
     * HELPERS
     * ============================
     */

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * ===============================================
     * UPDATED/MERGED LINK FUNCTION
     * ===============================================
     *
     * Generate a proper front-end link.
     * Yeh function ab har condition ko handle karta hai.
     */
    public function getLinkAttribute(): string
    {
        $baseUrl = '';

        // 1. Resolve Base URL
        if ($this->page) {
            // Priority 1: Explicitly linked Page
            $baseUrl = route('page.view', $this->page->slug);
        } elseif (!empty($this->url) && $this->url !== '#') {
            // Priority 2: Custom URL or Slug
            $url = $this->url;
            if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
                $baseUrl = $url;
            } elseif (str_starts_with($url, '/')) {
                $baseUrl = url($url);
            } else {
                $baseUrl = route('page.view', ['slug' => $url]);
            }
        } elseif ($this->parent_id && $this->parent) {
            // Priority 3: Inherit from parent (for section links)
            $parentLink = $this->parent->link;
            $baseUrl = explode('#', $parentLink)[0];
        }

        // 2. Append Section ID
        if ($this->section_id) {
            $section = str_starts_with($this->section_id, '#') ? substr($this->section_id, 1) : $this->section_id;
            return ($baseUrl ?: '') . '#' . $section;
        }

        return $baseUrl ?: '#';
    }

    /**
     * Get a human-readable status name.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Active' : 'Inactive';
    }
}
