<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

trait HasAvatar
{
    protected static function bootHasAvatar()
    {
        static::creating(function ($model) {
            $model->{$model->getAvatarField()} = \InitialsAvatarGenerator::name(
                $model->getNameInitialsField()
            )->generate();
        });

        static::updating(function ($model) {
            if (strpos($model->{$model->getAvatarField()}, 'IAG') !== false) {
                $model->{$model->getAvatarField()} = \InitialsAvatarGenerator::name(
                    $model->getNameInitialsField()
                )->generate();
            }
        });
    }

    public function getAvatarField()
    {
        if (method_exists($this, 'defineAvatarColumnName')) {
            return $this->defineAvatarColumnName();
        }

        return 'avatar';
    }

    public function getNameInitialsField()
    {
        if (method_exists($this, 'defineNameInitialsAvatarGenerator')) {
            return $this->defineNameInitialsAvatarGenerator();
        }

        return $this->name;
    }
}
