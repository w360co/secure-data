<?php

namespace W360\ImageStorage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageStorage extends Model
{
      use HasFactory;

      protected $guarded = [];

      public function model()
      {
         return $this->morphTo();
      }
}