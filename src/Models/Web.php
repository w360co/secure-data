<?php

namespace W360\SecureData\Models;

use Illuminate\Foundation\Auth\User as UserLaravel;
use W360\SecureData\Casts\Secure;
use W360\SecureData\Contracts\SecureDataEncrypted;
use W360\SecureData\Traits\HasEncryptedFields;

class Web extends UserLaravel implements SecureDataEncrypted
{
    use HasEncryptedFields;


    /**
     * The attributes that are mass assignable.
     *
     *@var array
     */
    protected $fillable = [
        'name',
        'url',
        'status',
        'user_id',
        'admin_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => Secure::class,
        'url' => Secure::class,
        'status' => Secure::class,
    ];




}
