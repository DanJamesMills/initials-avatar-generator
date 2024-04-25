<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

use DanJamesMills\InitialsAvatarGenerator\InitialsAvatarGenerator;
use Illuminate\Http\UploadedFile;

trait HasAvatar
{
    use HasAvatarUploader;

    /**
     * Boot the HasAvatar trait.
     */
    protected static function bootHasAvatar()
    {
        // Handle the avatar generation when creating a new model
        static::creating(function ($model) {
            $model->generateAvatarAndSet();
        });

        // Handle avatar updates when saving the model
        static::saving(function ($model) {
            // Retrieve the name of the avatar field from the model
            $avatarField = $model->getAvatarField();

            // Handle uploaded files: If the avatar field has a new UploadedFile, process it
            if (isset($model->attributes[$avatarField]) && $model->attributes[$avatarField] instanceof UploadedFile) {
                $model->{$avatarField} = $model->uploadAvatar(
                    $model->attributes[$avatarField],
                    config('initials-avatar-generator.storage_path'),
                    'local'
                );
            }

            if (! $model->isDirty($avatarField)) {
                if ($model->checkIfAvatarIsNotCustomImage($model->{$avatarField}) &&
                    $model->checkIfNameInitialsChanged()) {
                    $model->generateAvatarAndSet();
                }
            }
        });
    }

    /**
     * Upload file to the server.
     */
    private function uploadAvatar(UploadedFile $file, string $path, string $disk = 'public'): string
    {
        $fileName = $file->store($path, $disk);

        return pathinfo($fileName, PATHINFO_BASENAME);
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

        $this->{$this->getAvatarField()} = $generator->name($this->getNameInitialsField())
            ->filename($this->getFilenameToSaveAs())
            ->fileFormat('png')
            ->generate();
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
