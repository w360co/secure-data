<?php

/*
 * You can place your custom package configuration in here.
 */
return [



    /*
    |--------------------------------------------------------------------------
    |  Encrypt secret key
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'secret-key' => env('SECURE_SECRET_KEY', 'aa32b64207e2d1'),


    /*
    |--------------------------------------------------------------------------
    | Encrypt type (AES or DES)
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'encrypt-type' => env('SECURE_ENCRYPT_TYPE', 'AES'),



];