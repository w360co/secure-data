<?php

namespace W360\ImageStorage\Models;

use Illuminate\Foundation\Auth\User as UserLaravel;
use W360\ImageStorage\Traits\HasImages;

class User extends UserLaravel
{
    use HasImages;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphMany|object|null
     */
    public function getPhotoAttribute()
    {
        return $this->images()->first();
    }
}
