<?php

namespace App\Models;

use App\Models\Enums\RoleNameEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static count()
 * @method static find(string $user_id)
 * @property string $name
 * @property string $email
 * @property string $id
 * @property mixed $currencies
 */
class User extends Authenticatable
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id');
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(CryptoCurrency::class, 'wallets', 'user_id', 'currency_id');
    }

    private function hasRole(RoleNameEnum $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
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
