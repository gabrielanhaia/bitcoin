<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction related to the table 'transactions'.
 * @package App\Models
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com
 */
class Transaction extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'wallet_id',
        'type',
        'balance',
        'total_transaction',
        'profit_percentage',
        'total_profit',
        'requested_at',
        'processed_at',
        'wallet_id_origin',
        'wallet_id_destination',
        'observation'
    ];

    /** @var array $dates Date fields. */
    protected $dates = [
        'requested_at',
        'processed_at'
    ];
}
