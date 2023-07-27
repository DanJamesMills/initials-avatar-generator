<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

use DanJamesMills\InitialsAvatarGenerator\InitialsAvatarGenerator;

trait HasAvatar
{
    use HasAvatarUploader;

    /**
     * Boot the HasAvatar trait.
     */
    protected static function bootHasAvatar()
    {
        static::creating(function ($model) {
            $model->generateAvatarAndSet();
        });

        static::saving(function ($model) {
            if (! $model->isDirty($model->getAvatarField())) {
                if ($model->checkIfAvatarIsNotCustomImage($model->{$model->getAvatarField()}) && $model->checkIfNameInitialsChanged()) {
                    $model->generateAvatarAndSet();
                }
            }
        });
    }

    /**
     * Check if the avatar is not a custom image.
     */
    public function checkIfAvatarIsNotCustomImage(string $avatar): bool
    {
        return strpos($avatar, 'IAG') !== false;
    }

    /**
     * Get the avatar field name.
     */
    public function getAvatarField(): string
    {
        if (method_exists($this, 'defineAvatarColumnName')) {
            return $this->defineAvatarColumnName();
        }

        return 'avatar';
    }

    /**
     * Generate the avatar and set it to the model.
     */
    public function generateAvatarAndSet(): void
    {
        $generator = new InitialsAvatarGenerator();

        $this->{$this->getAvatarField()} = $generator->name($this->getNameInitialsField())->generate();
    }

    /**
     * Get the filename without the extension from the avatar.
     */
    public function getFilenameWithoutExtension(): string
    {
        return str_replace('IAG', '', pathinfo($this->{$this->getAvatarField()}, PATHINFO_FILENAME));
    }

    /**
     * Check if the name initials have changed.
     */
    public function checkIfNameInitialsChanged(): bool
    {
        return $this->getFilenameToSaveAs() !== $this->getFilenameWithoutExtension();
    }

    /**
     * Get the filename to save as.
     */
    public function getFilenameToSaveAs(): string
    {
        return md5($this->id.$this->getNameInitialsField());
    }

    /**
     * Get the name initials field.
     */
    public function getNameInitialsField(): string
    {
        if (method_exists($this, 'defineNameInitialsAvatarGenerator')) {
            return $this->defineNameInitialsAvatarGenerator();
        }

        return $this->name;
    }
}
