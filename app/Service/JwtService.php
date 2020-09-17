<?php

namespace App\Service;

use Firebase\JWT\JWT;

class JwtService extends BaseService
{
    public static function encode($user_id): string
    {
        $key = env('JWT_KEY');
        try {
            $sign = request()->header(env('APP_NAME'));
            if ($user_id) {
                $id = $user_id;
            } else {
                $params = JWT::decode($sign, $key, ['HS256']);
                $id = $params->id;
            }
            $payload = [
                'id' => $id,
                'exp' => time() + 900,
            ];
            return JWT::encode($payload, $key);
        } catch (\Exception $e) {
            return '';
        }
    }
}
