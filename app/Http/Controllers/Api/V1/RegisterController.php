<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Validator;

class RegisterController extends BaseController
{
    public function __construct(
        private readonly UserService $authenticationService
    )
    {
    }

    /**
     * Register new user
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function register(Request $request): Response|JsonResponse
    {
        $result = $this->authenticationService->register($request->all());
        if (get_class($result) === MessageBag::class) {
            return $this->sendError('Validation Error.', $result->toArray(), 400);
        }

        return $this->sendResponse(
            $this->createSuccessResponse($result),
            __('User registered successfully.')
        );
    }
}
