<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

use DanJamesMills\InitialsAvatarGenerator\InitialsAvatarGenerator;

trait HasAvatar
{
    protected static function bootHasAvatar()
    {
        static::creating(function ($model) {

            $model->generateAvatarAndSet();
        });

        static::updating(function ($model) {
            if (strpos($model->{$model->getAvatarField()}, 'IAG') != false) {
                return;
            }

            if (!$model->checkIfNameInitialsChanged()) {
                return;
            }

            $model->generateAvatarAndSet();
        });
    }

    public function getAvatarField()
    {
        if (method_exists($this, 'defineAvatarColumnName')) {
            return $this->defineAvatarColumnName();
        }

        return 'avatar';
    }

    public function generateAvatarAndSet()
    {
        $generator = new InitialsAvatarGenerator();
            
        $this->{$this->getAvatarField()} = $generator->name(
            $this->getNameInitialsField()
        )->generate();
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
