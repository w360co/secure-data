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
    function image(string $name, string $storage='default', string $size = '', bool $secure = null): string
    {
        $disk = Str::slug($storage);
        return URL::asset(str_replace('//', '/', 'storage/' . $disk . "/" . $size . "/" . $name), $secure);
    }
}


if (!function_exists('image_model')) {
    function image_model($model, string $size = '', bool $secure = null): string
    {
        if (isset($model->name) && isset($model->storage)) {
            $disk = Str::slug($model->storage);
            return URL::asset(str_replace('//', '/', 'storage/' . $disk . "/" . $size . "/" . $model->name), $secure);
        } else {
            $default = config('image-storage.default-image');
            if ($size) {
                list($width, $height) = config('image-storage.sizes.' . $size);
                return str_replace('{width}x{height}', $width . 'x' . $height, $default);
            } else {
                return str_replace('{width}x{height}', '300x300', $default);
            }
        }
    }
}

