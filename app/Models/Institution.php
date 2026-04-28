<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\HasInstitutionScope;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $category
 * @property string|null $curriculum
 * @property string|null $city
 * @property string|null $featured_image
 * @property string|null $year_of_establishment
 * @property string|null $growth_graph
 * @property bool $status
 * @property string|null $website
 * @property string|null $phone
 * @property string|null $address
 * @property array|null $social_links
 * @property string|null $institutional_journey
 * @property array|null $about_sections
 * @property string|null $academic_activities
 * @property array|null $activities_facilities_blocks
 * @property string|null $co_curricular_activities
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $iso_certification
 * @property string|null $breadcrumb_image
 * @property string|null $tagline
 * @property string|null $academic_diary_pdf
 * @property array|null $results_awards
 * @property string|null $google_maps_link
 * @property string|null $breadcrumb_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $category_label
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InstitutionResult[] $results
 * @property-read \App\Models\InstitutionPrincipal|null $principal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InstitutionPTAMember[] $ptaMembers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InstitutionAward[] $awards
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InstitutionGallery[] $galleries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InstitutionSection[] $sections
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InstitutionStaff[] $staffs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Institution extends Model
{
    use HasInstitutionScope;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'curriculum',
        'city',
        'featured_image',
        'year_of_establishment',
        'growth_graph',
        'status',
        'website',
        'phone',
        'address',
        'social_links',
        'institutional_journey',
        'about_sections',
        'academic_activities',
        'activities_facilities_blocks',
        'co_curricular_activities',
        'meta_title',
        'meta_description',
        'iso_certification',
        'breadcrumb_image',
        'tagline',
        'academic_diary_pdf',
        'results_awards',
        'google_maps_link',
        'breadcrumb_note',
    ];

    protected $casts = [
        'status' => 'boolean',
        'social_links' => 'json',
        'about_sections' => 'json',
        'activities_facilities_blocks' => 'json',
        'results_awards' => 'json',
    ];

    public static function getCategories()
    {
        return [
            'A' => 'Pre-Primary & Primary Schools',
            'B' => 'Secondary Schools',
            'C' => 'Junior Colleges',
            'D' => 'Degree College',
            'E' => 'Distance Learning Centre – YCMOU',
            'F' => 'Information Centre – TMV Pune',
            'G' => 'Gandhian Study Centre',
            'H' => 'Dr. W. S. Matkar Sangeet Vidyalaya',
            'I' => 'Sports Academy',
        ];
    }

    public function getCategoryLabelAttribute()
    {
        $categories = self::getCategories();
        return $categories[$this->category] ?? $this->category;
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($institution) {
            if (empty($institution->slug)) {
                $institution->slug = Str::slug($institution->name);
            }
        });

        // Clear cache on any change
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_institutions');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_institutions');
        });
    }

    public function results(): HasMany
    {
        return $this->hasMany(InstitutionResult::class);
    }

    public function principal(): HasOne
    {
        return $this->hasOne(InstitutionPrincipal::class);
    }

    public function ptaMembers(): HasMany
    {
        return $this->hasMany(InstitutionPTAMember::class);
    }

    public function awards(): HasMany
    {
        return $this->hasMany(InstitutionAward::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(InstitutionGallery::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(InstitutionSection::class);
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(InstitutionStaff::class);
    }

    /**
     * Pending actions for this institution.
     */
    public function pendingActions()
    {
        return $this->morphMany(PendingAction::class, 'model')->where('status', '=', 'pending');
    }

    /**
     * Check if there are any pending changes for this institution.
     */
    public function hasPendingChanges(): bool
    {
        return $this->pendingActions()->exists();
    }

    /**
     * Users (Makers/Approvers) assigned to this institution.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

