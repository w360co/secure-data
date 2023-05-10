<?php

namespace W360\SecureData\Models;

use Illuminate\Foundation\Auth\User as UserLaravel;
use W360\SecureData\Casts\Secure;
use W360\SecureData\Casts\SecureFloat;
use W360\SecureData\Contracts\SecureDataEncrypted;
use W360\SecureData\Traits\HasEncryptedFields;

class Admin extends UserLaravel implements SecureDataEncrypted
{
    use HasEncryptedFields;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
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
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email' => Secure::class,
        'identifier' => Secure::class,
        'first_name' => Secure::class,
        'last_name' => Secure::class,
        'salary' => SecureFloat::class,
        'status' => Secure::class,
    ];


    /**
     * Get all of the tags for the post.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'webs')->withPivot([
            'name',
            'url',
            'status'
        ]);
    }


}
