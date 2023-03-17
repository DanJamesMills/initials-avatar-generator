<?php

namespace DanJamesMills\InitialsAvatarGenerator\Tests\Models;

use DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar;
use Illuminate\Foundation\Auth\User as UserModel;

class User extends UserModel
{
    use HasAvatar;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Used to define the fields of which initials avatar
     * generator will create initials from.
     */
    protected function defineNameInitialsAvatarGenerator(): string
    {
        return $this->name;
    }
}
