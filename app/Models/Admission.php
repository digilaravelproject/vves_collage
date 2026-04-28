<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $mobile_prefix
 * @property string $mobile_no
 * @property string|null $discipline
 * @property string|null $level
 * @property string|null $programme
 * @property bool $authorised_contact
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool|null delete()
 * @mixin Illuminate\Database\Eloquent\Model
 * @mixin Illuminate\Database\Eloquent\Builder
 */
class Admission extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_prefix',
        'mobile_no',
        'discipline',
        'level',
        'programme',
        'authorised_contact',
        'status',
        'verified_at'
    ];

    protected $casts = [
        'authorised_contact' => 'boolean',
        'verified_at' => 'datetime',
    ];
}
