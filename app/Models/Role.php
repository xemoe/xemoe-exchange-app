<?php

namespace App\Models;

use App\Models\Enums\RoleNameEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static create(array $array)
 * @method static where(array $array)
 */
class Role extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'name' => RoleNameEnum::class,
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
