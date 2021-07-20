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
     *
     * @return void
     */
    private function saveAvatarFileToDisk(): void
    {
        $this->generatedFilename = $this->generateRandomFilename();

        $this->file->move(storage_path('app/public/avatars'), $this->generatedFilename);
    }


    private function resizeImage()
    {
        \Image::make(storage_path('app/public/avatars/' . $this->generatedFilename))
            ->resize(800, 800)
            ->save();
    }

    /**
     * Generates a random file name.
     *
     * @return string
     */
    protected function generateRandomFilename(): string
    {
        return sha1($this->file->getClientOriginalName() . time()). '.' . $this->file->getClientOriginalExtension();
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
