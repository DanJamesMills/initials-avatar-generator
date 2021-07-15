<?php

namespace DanJamesMills\InitialsAvatarGenerator;

class AvatarUploader
{
    /**
     * @var \Illuminate\Http\Request;
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

        $this->file->move(public_path('avatars'), $this->generatedFilename);
    }


    private function resizeImage()
    {
        \Image::make(public_path('avatars/' . $this->generatedFilename))
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
    
    public function handle(): string
    {
        $this->saveFile();

        $this->resizeImage();

        return $this->getGeneratedFilename();
    }
}
