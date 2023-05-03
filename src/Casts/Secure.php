<?php

namespace W360\SecureData\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Secure implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}