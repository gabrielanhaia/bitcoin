<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeRate responsible for storing the exchange rates updated.
 * @package App\Models
 */
class ExchangeRate extends Model
{
    /** @var string $table Table name. */
    protected $table = 'exchange_rates';

    /** @var array $fillable */
    protected $fillable = [
        'name',
        'currency_id',
        'amount',
        'bitcoin_amount',
        'datetime',
        'created_at',
        'updated_at',
    ];

    /** @var array $dates Date fields. */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
