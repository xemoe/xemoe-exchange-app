<?php

namespace App\Services;

use App\Models\Enums\RoleNameEnum;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\UserRolesRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserRolesRepository $userRolesRepository,
    )
    {
    }

    /**
     * @param array $input
     * @return MessageBag|User
     */
    public function register(array $input): MessageBag|User
    {
        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:255',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $user = $this->userRepository->create(
            $input['name'],
            $input['email'],
            $input['password']
        );

        if ($user->exists) {
            $this->userRolesRepository->setRole($user, RoleNameEnum::Regular);
        }

        return $user;
    }
}
