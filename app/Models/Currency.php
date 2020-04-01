<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Currency responsible for the model who deal with different currencies available.
 * @package App\Models
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Currency extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    /** @var array $dates Date fields. */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
