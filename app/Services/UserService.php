<?php

namespace App\Services;

use App\Models\Enums\RoleNameEnum;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
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
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
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
            $user->roleUser()->create([
                'role_id' => Role::where(['name' => RoleNameEnum::Regular])->first()->id,
            ]);
        }

        return $user;
    }
}
