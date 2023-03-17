<?php

namespace DanJamesMills\InitialsAvatarGenerator\Tests\Models;

use Illuminate\Foundation\Auth\User as UserModel;
use DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar;

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
     *
     * @return string
     */
    protected function defineNameInitialsAvatarGenerator(): string
    {
        return $this->name;
    }
}
