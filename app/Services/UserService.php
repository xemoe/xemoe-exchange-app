<?php

namespace App\Services;

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
     * @return MessageBag|mixed
     */
    public function register(array $input): mixed
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

        return $this->userRepository->create(
            $input['name'],
            $input['email'],
            $input['password']
        );
    }
}
