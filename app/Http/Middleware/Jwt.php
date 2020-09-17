<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Jwt
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    final public function handle(Request $request, Closure $next)
    {
        Log::info('params', $request->all());
        $key = env('JWT_KEY');
        $sign = $request->header(env('APP_NAME'));
        try {
            $params = \Firebase\JWT\JWT::decode($sign, $key, ['HS256']);
            $user = UserModel::whereId($params->id)->first();
            if (!$user) {
                return response('', 403);
            }
            $request->attributes->add(['user' => $user]);
            return $next($request);
        } catch (\Exception $e) {
            return response('', 403);
        }
    }
}
