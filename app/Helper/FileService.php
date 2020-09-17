<?php

namespace App\Helper;

use OSS\OssClient;

class FileService extends BaseHelper
{

    public static function getFile(string $filename): string
    {
        return self::getImgUrl('', $filename);
    }

    private static function oss(): OssClient
    {
        $access_id = env('ALI_ACCESS_KEY_ID');
        $access_key = env('ALI_ACCESS_KEY_SECRET');
        $domain = env('ALI_OSS_DOMAIN');
        $oss = new OssClient($access_id, $access_key, $domain);
        $oss->setUseSSL(true);
        return $oss;
    }

    private static function getImgUrl(string $path, string $filename, string $style = ''): string
    {
        if (!$filename) {
            return '';
        }
        $bucket = env('ALI_OSS_IMG_BUCKET_NAME');
        if (!$path) {
            $object = $filename;
        } else {
            $object = "{$path}/{$filename}";
        }
        $opt = [];
        if ($style) {
            $opt = [OssClient::OSS_PROCESS => $style];
        }
        $img_url = self::oss()->signUrl($bucket, $object, 999999999, OssClient::OSS_HTTP_GET, $opt);
        $img_url_pos = strpos($img_url, '?');
        if ($img_url_pos !== false) {
            $img_url = substr($img_url, 0, $img_url_pos);
        }
        return $img_url;
    }

    public static function base64PutImg(string $content, $options = null): string
    {
        $md5 = md5($content);
        $res = self::upload($md5, $content, $options);
        //todo 如何判断失败
        return $md5;
    }

    public static function upload($object, $content, $options = null)
    {
        $bucket = env('ALI_OSS_IMG_BUCKET_NAME');
        if (self::oss()->doesObjectExist($bucket, $object)) {
            return $object;
        }
        return self::oss()->putObject($bucket, $object, $content, $options);
    }
}
