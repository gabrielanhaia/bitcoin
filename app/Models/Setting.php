<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Setting responsible for the model with system settings.
 * @package App\Models
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
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
