<?php

namespace W360\SecureData\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \W360\SecureData\Facades\Image
 */
class ImageST extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'imageSt';
    }
}
