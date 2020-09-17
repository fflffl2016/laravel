<?php
declare (strict_types=1);

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Models\BatchCouponModel;
use App\Models\BatchModel;
use App\Models\ContractModel;
use App\Models\OrderModel;
use App\Models\PaymentModel;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/other/status",
     *     tags ={"其他"},
     *     summary="状态名称",
     *     operationId="/api/other/status",
     *     @OA\Response(response="200", description="")
     * )
     * @return JsonResponse
     */
    final public function run(): JsonResponse
    {
        return $this->jsonSuccessResponse([
        ]);
    }

    private function oye(array $a): array
    {
        $b = [];
        foreach ($a as $k => $v) {
            $b[] = [
                'name' => $v,
                'code' => $k,
            ];
        }
        return $b;
    }
}
