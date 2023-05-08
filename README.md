# W360 Secure Data

Library to Encrypt Database Fields in Mysql using Advanced Encryption Standard (AES) and Data Encryption Standard (DES)

[![runtest](https://github.com/w360co/secure-data/actions/workflows/laravel.yml/badge.svg)](https://github.com/w360co/secure-data/actions/workflows/laravel.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/w360/secure-data)](https://packagist.org/packages/w360/secure-date)
[![Latest Stable Version](https://img.shields.io/packagist/v/w360/secure-data)](https://packagist.org/packages/w360/secure-date)
[![License](https://img.shields.io/packagist/l/w360/secure-data)](https://packagist.org/packages/w360/secure-date)

# Table of Contents
<!-- TOC -->
- [Installation](#Installation)
- [Examples](#Examples)
- [Features](#Features)
- [Contributors](#Contributors)
- [License](#License)
<!-- /TOC -->

## Installation

    > composer require w360/secure-data

## Examples
- Example of use
```PHP
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use W360\SecureData\Traits\HasEncryptedFields;


class User extends Authenticatable
{
    use HasEncryptedFields;


    /**
     * The attributes that are mass assignable.
     *
     *@var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'identifier',
        'salary',
        'status',
        'password'
    ];
    
    /**
     * The attributes that should be encrypted.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email' => Secure::class,
        'identifier' => Secure::class,
        'first_name' => Secure::class,
        'last_name' => Secure::class,
        'salary' => Secure::class,
        'status' => Secure::class,
    ];
    
}
```

## Features

- It allows to perform SQL queries encrypting and decrypting fields in a natural way only using MySql encryption methods

## Contributors

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

##  License

The MIT License (MIT)

Copyright (c) 2023 Elbert Tous

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
