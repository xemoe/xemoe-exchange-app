<?php

namespace App\Models;

use App\Models\Enums\RoleNameEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static count()
 * @property string $name
 */
class User extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUser::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    private function hasRole(RoleNameEnum $roleName): bool
    {
        return $this->roleUsers()->whereHas('role', static function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->exists();
    }

    public function hasRegularRole(): bool
    {
        return $this->hasRole(RoleNameEnum::Regular);
    }

    public function hasAdminRole(): bool
    {
        return $this->hasRole(RoleNameEnum::Admin);
    }
}
