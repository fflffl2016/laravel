<?php
declare (strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/info",
     *     tags ={"user"},
     *     summary="用户信息",
     *     operationId="userinfo",
     *     @OA\Parameter(
     *         name="id",
     *         description="用户id",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             default="1"
     *         )
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     * @param Request $req
     * @return JsonResponse
     */
    final public function run(Request $req): JsonResponse
    {
        return $this->jsonSuccessResponse('oye');
    }
}
