<?php

namespace App\Helper;


use Illuminate\Support\Facades\Request;

class Cache extends BaseHelper
{
    public static function categoryOption(): string
    {
        return "categoryOption";
    }
    public static function categName(): string
    {
        return "categoryName";
    }

    public static function token(string $token): string
    {
        return "token_{$token}";
    }


    public static function get($key, $callback, $minutes)
    {
        $json = \Cache::get($key);
        if (!$json || Request::get('nocache')) {
            $ret = $callback();
            $json = json_encode($ret);
            \Cache::put($key, $json, $minutes);
        }
        return json_decode($json);
    }
}
