<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

trait HasAvatar
{
    /* Map default colums */
    protected static $avatarField = 'avatar';

    protected static function bootHasAvatar()
    {
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                $model->{static::$avatarField} = \InitialsAvatarGenerator::name(
                    $model->defineNameInitialsAvatarGenerator()
                )
                    ->generate();
            });
        }

        if (!app()->runningInConsole()) {
            static::updating(function ($model) {
                $model->{static::$avatarField} = \InitialsAvatarGenerator::name(
                    $model->defineNameInitialsAvatarGenerator()
                )
                    ->generate();
            });
        }
    }
}
