<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

trait HasAvatar
{
    /* Map default colums */
    protected static $userIdField = 'user_id';

    protected static function bootHasAvatar()
    {
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                \InitialsAvatarGenerator::name('Danny Mills')
                    ->rounded()
                    ->generate();
                
                // $model->{static::$userIdField} = Auth::User()->id;
            });
        }
    }
}
