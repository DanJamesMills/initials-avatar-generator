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
            if (strpos($model->{$model->getAvatarField()}, 'IAG') == false) {
                return;
            }

            if (!$model->checkIfNameInitialsChanged()) {
                return;
            }

            $model->{$model->getAvatarField()} = \InitialsAvatarGenerator::name(
                $model->getNameInitialsField()
            )->filename($model->getFilenameToSaveAs())
            ->generate();
        });
    }

    public function getAvatarField()
    {
        if (method_exists($this, 'defineAvatarColumnName')) {
            return $this->defineAvatarColumnName();
        }

        return 'avatar';
    }

    public function getFilenameWithoutExtension(): string
    {
        return str_replace('IAG', '', pathinfo($this->{$this->getAvatarField()}, PATHINFO_FILENAME));
    }

    public function checkIfNameInitialsChanged()
    {
        return $this->getFilenameToSaveAs() != $this->getFilenameWithoutExtension();
    }

    public function getFilenameToSaveAs()
    {
        return md5($this->id . $this->getNameInitialsField());
    }

    public function getNameInitialsField()
    {
        if (method_exists($this, 'defineNameInitialsAvatarGenerator')) {
            return $this->defineNameInitialsAvatarGenerator();
        }

        return $this->name;
    }
}
