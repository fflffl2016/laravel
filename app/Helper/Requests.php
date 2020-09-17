<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use stdClass;

class Requests extends BaseHelper
{

    public static function returnErrStd(string $msg): stdClass
    {
        $err = new \stdClass();
        $err->status = 'error';
        $err->message = trim($msg);
        return $err;
    }

    public static function success(): stdClass
    {
        $success = new stdClass();
        $success->status = 'success';
        return $success;
    }

    /**
     * @param $password
     *
     * @return bool|string
     * 生成密码
     */
    public static function makePassword(string $password): string
    {
        $hash = \hash('sha256', $password, true);
        return base64_encode($hash);
    }

    public static function md5(string $str): string
    {
        return md5(md5(md5($str)));
    }

    /**
     * 10.55元转成1055分
     *
     * @param $price float
     *
     * @return int
     */
    public static function priceConvertBig($price): int
    {
        return intval(round($price * 100));
    }

    /**
     * 1055分转成10.55元
     *
     * @param $price int
     *
     * @return string
     */
    public static function priceConvertSmall($price): string
    {
        return (string)($price / 100);
    }

    /**
     * 检查手机号是否为11位数字
     *
     * @param string $mobile 手机号
     *
     * @return bool
     */
    public static function checkMobile(string $mobile): bool
    {
        if (preg_match('/^1\d{10}$/', $mobile)) {
            return true;
        }

        return false;
    }

    /**
     * @param $passwd
     * 检查密码 6到26位数字英文特殊字符
     * @return bool
     */
    public static function checkPasswd(string $passwd): bool
    {
        return (preg_match('/^[-\da-zA-Z`=\\\[\];\',\/.~!@#$%^&*()_+|{}:"<>?]{6,26}$/', $passwd)) ? true : false;
    }

    public static function changeMobile(string $mobile): string
    {
        $mobile = str_replace([' ', '+86', '-'], '', $mobile);
        if ($mobile[0] == 0) {
            $mobile = substr($mobile, 1, 100);
        }
        return $mobile;
    }

    public static function createToken(): string
    {
        return self::md5(uniqid(random_int(1000000, 9999999), true));
    }

    public static function ids($data, $key): array
    {
        $ids = array();
        foreach ($data as $d) {
            if (isset($d->$key)) {
                $ids[$d->$key] = $d;
            }
        }
        return $ids;
    }

    public static function arrayToObject(array $arr): stdClass
    {
        return (object)array_map(__FUNCTION__, $arr);
    }


    public static function dateFormat(string $datetime): string
    {
        return date('Y.m.d H:i', strtotime($datetime));
    }

    public static function dateFormatList(string $datetime): string
    {
        $datetime_tms = strtotime($datetime);
        $dur = time() - $datetime_tms;
        if ($dur < 0) return $datetime;
        if ($dur < 60) return '刚刚';
        if ($dur < 3600) return floor($dur / 60) . '分钟前';
        if ($dur < 86400) return floor($dur / 3600) . '小时前';
        if ($dur < 172800) return '昨天';
        if ($dur < 864000) return floor($dur / 86400) . '天前';
        if (date('Y') == date('Y', $datetime_tms)) {
            return date('n-j G:i', $datetime_tms);
        }
        return date('Y-n-j G:i', $datetime_tms);
    }

    /**
     * 生成随机数
     *
     * @param int $length
     * @param bool|true $only_number 是否只有数字
     *
     * @return int|string
     */
    public static function makeRandStr(int $length = 4, bool $only_number = true): string
    {
        if ($length <= 0) return '';
        $str = $only_number ? '0123456789' : '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = min($length, strlen($str));
        return substr(str_shuffle($str), 0, $length);
    }


    public static function getOrderNo(int $order_id, string $prefix = 'order'): string
    {
        return $prefix . time() . $order_id . random_int(100000, 999999);
    }

    public static function priceFormat($price): string
    {
        return sprintf('%.2f', $price);
    }

    /*
     * 根据两点间的经纬度计算距离
     *
     * @param float $lat 纬度值
     * @param float $lng 经度值
     *
     * @return int 米
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000;
        $lat1 = ($lat1 * pi()) / 180;
        $lng1 = ($lng1 * pi()) / 180;
        $lat2 = ($lat2 * pi()) / 180;
        $lng2 = ($lng2 * pi()) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculate = $earthRadius * $stepTwo;
        return abs(round($calculate));
    }

    public static function log(string $msg, array $context = []): void
    {
        echo date('Y-m-d H:i:s ') . $msg . "\n";
        if ($context) {
            print_r($context);
        }
    }

    public static function wxDecryptData(string $encryptedData, string $iv, string $sessionKey): string
    {
        $aesKey = base64_decode($sessionKey);
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        Log::info('wx decrypt data ' . $result);
        Log::info('wx decrypt data str ' . $encryptedData);
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }
}
