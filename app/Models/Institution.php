<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Institution extends Model
{
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
}
