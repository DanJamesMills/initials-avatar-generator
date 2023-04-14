<?php

namespace DanJamesMills\InitialsAvatarGenerator;

use Illuminate\Http\UploadedFile;

class AvatarUploader
{
    /**
     * @var \Illuminate\Http\UploadedFile;
     */
    private $file;

    /**
     * A generated file name used
     * for saving avatar file.
     *
     * @var string
     */
    private $generatedFilename;

    /**
     * Save avatar file
     * to disk.
     */
    private function saveAvatarFileToDisk(): void
    {
        $this->generatedFilename = $this->generateRandomFilename();

        $this->file->move(config('initials-avatar-generator.storage_path'), $this->generatedFilename);
    }

    private function resizeImage()
    {
        \Image::make(config('initials-avatar-generator.storage_path').$this->generatedFilename)
            ->resize(config('initials-avatar-generator.width'), config('initials-avatar-generator.height'))
            ->save();
    }

    /**
     * Generates a random file name.
     */
    protected function generateRandomFilename(): string
    {
        return sha1($this->file->getClientOriginalName().time()).'.'.$this->file->getClientOriginalExtension();
    }

    public function getGeneratedFilename(): string
    {
        return $this->generatedFilename;
    }

    public function handle(UploadedFile $file): string
    {
        $this->file = $file;

        $this->saveAvatarFileToDisk();

        $this->resizeImage();

        return $this->getGeneratedFilename();
    }
}
