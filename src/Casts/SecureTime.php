<?php

namespace W360\SecureData\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class SecureTime implements CastsAttributes
{

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return mixed|null
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed|null $value
     * @param array $attributes
     * @return mixed|null
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}