<?php

namespace DanJamesMills\InitialsAvatarGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DanJamesMills\InitialsAvatarGenerator\Skeleton\SkeletonClass
 */
class InitialsAvatarGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'initials-avatar-generator';
    }
}
