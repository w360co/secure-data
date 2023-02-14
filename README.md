# W360 Image Storage

Base module for w360 projects using react

[![runtest](https://github.com/w360co/image-storage/actions/workflows/laravel-test.yml/badge.svg?branch=main)](https://github.com/w360co/image-storage/actions/workflows/laravel-test.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/w360/image-storage)](https://packagist.org/packages/w360/image-storage)
[![Latest Stable Version](https://img.shields.io/packagist/v/w360/image-storage)](https://packagist.org/packages/w360/image-storage)
[![License](https://img.shields.io/packagist/l/w360/image-storage)](https://packagist.org/packages/w360/image-storage)

# Table of Contents
<!-- TOC -->
- [Features](#Features)
- [License](#License)
<!-- /TOC -->

## Installation

    > composer require w360/image-storage

## Examples
- Example of use uploading a profile photo for a user
```PHP
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use W360\ImageStorage\Facades\ImageST;

class TestController extends Controller
{
    private function saveProfile(Request $request){
        if($request->hasFile('photo') and Auth::check()){
            $storage = 'photos';
            $photo = $request->photo;
            $user = User::findOrFail(Auth::user()->id);
            ImageST::updateOrCreate($photo, $storage, $user);
        }
    }
}
```

```PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use W360\ImageStorage\Models\ImageStorage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasImages;
    
    public function getPhotoAttribute(){
        return $this->images()->first();
    } 
}
```
```html
show uploaded image 
@php($photo = Auth::user()->photo)
@if($photo)
<img src="{{ image($photo->name, $photo->storage) }}" alt="image uploaded with w360/image-storage" />
get xs image
<img src="{{ image($photo->name, $photo->storage, 'xs') }}" alt="image uploaded with w360/image-storage xs size" />
...
get xxl image
<img src="{{ image($photo->name, $photo->storage, 'xxl') }}" alt="image uploaded with w360/image-storage xxl size" />
@endif
```
## Features

- Allows uploading images to storage easily
- Allows you to generate multiple sizes of an image with its corresponding quality settings

## Libraries

- Image Intervention https://image.intervention.io/v2/introduction/installation

##  License

The MIT License (MIT)

Copyright (c) 2023 W360 S.A.S

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
