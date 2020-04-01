<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting
 * @package App\Models
 *
 * Class responsible for the model with system settings.
 */
class Setting extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'name',
        'value',
        'created_at',
        'updated_at'
    ];

    /** @var array $dates Date fields. */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
