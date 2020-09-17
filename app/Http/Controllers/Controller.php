<?php

namespace App\Http\Controllers;

use App\Helper\Requests;
use App\Service\JwtService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    final public function jsonSuccessResponse($result = [], $user_id = 0): JsonResponse
    {
        if (!$result) {
            $result = (object)null;
        }
        $success = Requests::success();
        $success->result = $result;
        $jwt = JwtService::encode($user_id);
        return response()->json($success, 200, ['rights-coupon' => $jwt]);
    }

    final public function jsonErrorResponse(string $msg = ''): JsonResponse
    {
        $error = Requests::returnErrStd($msg);
        return response()->json($error);
    }
}
