<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(string[] $array)
 * @method static make(string[] $array)
 * @method static where(string[] $array)
 * @method static count()
 * @method static whereIn(string $string, array|string[] $symbols)
 * @property string $id
 * @property string $symbol
 */
class FiatCurrency extends Model
{
    use HasUuids, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'symbol',
    ];
}
