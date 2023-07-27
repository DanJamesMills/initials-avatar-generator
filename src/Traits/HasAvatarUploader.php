<?php

namespace DanJamesMills\InitialsAvatarGenerator\Traits;

use DanJamesMills\InitialsAvatarGenerator\AvatarUploader;

trait HasAvatarUploader
{
    /**
     * Upload the avatar using the given file.
     *
     * @param  mixed  $file
     */
    public function uploadAvatar($file): string
    {
        $uploader = new AvatarUploader();

        return $uploader->handle($file);
    }
}
