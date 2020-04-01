<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 * @package App\Models
 *
 * Class responsible for the user wallets.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Wallet extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'created_at',
        'updated_at'
    ];

    /** @var array $dates Date fields. */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
