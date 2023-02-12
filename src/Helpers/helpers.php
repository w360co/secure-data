<?php

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


/**
 * helper to obtain images of different sizes and with any storage
 * @param $name
 * @param string $storage | disk o directory
 * @param string $size | thumbnail, xs, sm, md, lg, xl, xxl
 * @param bool|null $secure | true to https or false to http
 * @return array|string|string[]
 */
if (!function_exists('image')) {
    function image($name, string $storage = 'default', string $size = '', bool $secure = null): string
    {
        $disk = Str::slug($storage);
        return URL::asset(str_replace('//', '/', $disk . "/" . $size . "/" . $name), $secure);
    }
}
