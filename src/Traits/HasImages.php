<?php

namespace W360\ImageStorage\Traits;

use W360\ImageStorage\Models\ImageStorage;

trait HasImages
{

    public function images(){
         return $this->morphMany(ImageStorage::class, 'model');
    }
}