<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends BaseController
{
    public function __construct(
        private readonly UserService $authenticationService
    )
    {
    }

    private const HTTP_CODE_UNAUTHORIZED = 401;

    /**
     * Login user
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function login(Request $request): Response|JsonResponse
    {
        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return $this->sendResponse(
                $this->createAuthSuccessResponse(Auth::user()),
                __('User logged in successfully.')
            );
        } else {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorised'], self::HTTP_CODE_UNAUTHORIZED);
        }
    }
}
