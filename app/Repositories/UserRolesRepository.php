<?php

namespace App\Repositories;

use App\Models\Enums\RoleNameEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

class UserRolesRepository
{
    /**
     * @param User $user
     * @param RoleNameEnum $roleName
     * @return void
     */
    public function setRole(User $user, RoleNameEnum $roleName): void
    {
        //
        // Prevent from creating duplicate records
        //
        if ($user->roles()->where(['name' => $roleName])->exists()) {
            return;
        }

        $user->roles()->attach(
            Role::where(['name' => $roleName])->first()->id,
            ['id' => Str::orderedUuid()]
        );
    }

    /**
     * @param User $user
     * @param RoleNameEnum $roleName
     * @return void
     */
    public function unsetRole(User $user, RoleNameEnum $roleName): void
    {
        $user->roles()->detach(
            Role::where(['name' => $roleName])->first()->id
        );
    }
}
