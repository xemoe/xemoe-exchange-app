<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 * @method trashed()
 * @method static find(string $id)
 * @property string $id
 * @property string $user_id
 */
class Wallet extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'currency_id',
        'address',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:30',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): HasOne
    {
        return $this->hasOne(CryptoCurrency::class, 'id', 'currency_id');
    }
}
