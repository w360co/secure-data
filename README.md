# W360 SUPPORT MODULE

Base module for w360 projects using react

# Table of Contents
<!-- TOC -->
- [Features](#Features)
- [License](#License)
<!-- /TOC -->

## Installation

    > composer required w360/image-storage

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
            ImageST::save($photo, $storage, $user);
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
<img src="{{ image($photo->name, $photo->storage) }}" alt="image uploaded with w360/image-storage" />
get xs image
<img src="{{ image($photo->name, $photo->storage, 'xs') }}" alt="image uploaded with w360/image-storage xs size" />
...
get xxl image
<img src="{{ image($photo->name, $photo->storage, 'xxl') }}" alt="image uploaded with w360/image-storage xxl size" />
```
## Features

- Configure Laravel with React
- Multi Language Support
- Axios for React Configuration and Request Interception

## Libraries

- Image Intervention https://image.intervention.io/v2/introduction/installation
- Internationalization i18next https://www.i18next.com/

##  License

The MIT License (MIT)

Copyright (c) 2023 W360 S.A.S

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
