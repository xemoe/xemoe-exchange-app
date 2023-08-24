<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

abstract class BaseController extends Controller
{
    /**
     * success response method.
     * @param $result
     * @param $message
     * @return Response|JsonResponse
     */
    public function sendResponse($result, $message): Response|JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    protected function createSuccessResponse(User $user): array
    {
        return [
            'token' => AuthenticationService::getToken($user),
            'name' => $user->name,
        ];
    }

    /**
     * return error response.
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, array $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
