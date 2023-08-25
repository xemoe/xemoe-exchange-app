<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(string[] $array)
 * @method static truncate()
 * @property CryptoCurrency $baseCurrency
 * @property CryptoCurrency $quoteCurrency
 * @property string $pair_symbol
 */
class TradingPair extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'base_currency_symbol',
        'base_currency_type',
        'base_currency_id',
        'quote_currency_symbol',
        'quote_currency_type',
        'quote_currency_id',
        'pair_symbol',
    ];

    public function baseCurrency(): MorphTo
    {
        return $this->morphTo('base_currency', 'base_currency_type', 'base_currency_id');
    }

    public function quoteCurrency(): MorphTo
    {
        return $this->morphTo('quote_currency', 'quote_currency_type', 'quote_currency_id');
    }
}
