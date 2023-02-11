<?php

namespace W360\ImageStorage;

use Illuminate\Support\Facades\Facade;

/**
 * @see \W360\ImageStorage\Skeleton\SkeletonClass
 */
class ImageStorageFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'image-storage';
    }
}
