<?php

namespace App\Services;

use App\Models\User;

class AuthenticationService
{
    public static function getToken(User $user): string
    {
        return $user->createToken(config('app.name'))->plainTextToken;
    }
}
