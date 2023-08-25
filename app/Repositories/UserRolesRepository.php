<?php

namespace App\Repositories;

use App\Models\Enums\RoleNameEnum;
use App\Models\Role;
use App\Models\User;

class UserRolesRepository
{
    /**
     * @param User $user
     * @param RoleNameEnum $roleName
     * @return void
     */
    public function setRole(User $user, RoleNameEnum $roleName): void
    {
        $user->roleUsers()->create([
            'role_id' => Role::where(['name' => $roleName])->first()->id,
        ]);
    }

    /**
     * @param User $user
     * @param RoleNameEnum $roleName
     * @return void
     */
    public function unsetRole(User $user, RoleNameEnum $roleName): void
    {
        $user->roleUsers()->where([
            'role_id' => Role::where(['name' => $roleName])->first()->id
        ])->delete();
    }
}
