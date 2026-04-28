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
 * @property string|null $level
 * @property string|null $discipline
 * @property string|null $programme
 * @property string|null $message
 * @property bool $authorised_contact
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Enquiry query()
 * @method static \Illuminate\Database\Eloquent\Builder|Enquiry latest($column = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|Enquiry where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Enquiry create($attributes = [])
 * @method static Enquiry|null find($id, $columns = ['*'])
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Enquiry extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_prefix',
        'mobile_no',
        'level',
        'discipline',
        'programme',
        'message',
        'authorised_contact',
        'status',
        'verified_at'
    ];

    protected $casts = [
        'authorised_contact' => 'boolean',
        'verified_at' => 'datetime',
    ];
}
