<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

trait HasAvatar
{
    protected static function bootHasAvatar()
    {
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                $model->{$model->getAvatarField()} = \InitialsAvatarGenerator::name(
                    $model->defineNameInitialsAvatarGenerator()
                )->generate();
            });
        }

        if (!app()->runningInConsole()) {
            static::updating(function ($model) {
                if (strpos($model->{$model->getAvatarField()}, 'IAG') !== false) {
                    $model->{$model->getAvatarField()} = \InitialsAvatarGenerator::name(
                        $model->defineNameInitialsAvatarGenerator()
                    )->generate();
                }
            });
        }
    }

    protected function getAvatarField()
    {
        if (method_exists($this, 'defineAvatarColumnName')) {
            return $this->defineAvatarColumnName();
        }

        return 'avatar';
    }
}
