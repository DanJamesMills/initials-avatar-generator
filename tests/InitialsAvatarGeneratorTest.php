<?php

namespace DanJamesMills\InitialsAvatarGenerator\Tests;

use DanJamesMills\InitialsAvatarGenerator\Tests\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InitialsAvatarGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_avatar_is_created_when_user_is_created()
    {
        $user = User::Create([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => 'test',
        ]);

        $this->assertNotEmpty($user->avatar);

        $avatarFilePath = config('initials-avatar-generator.storage_path').'/'.$user->avatar;

        $this->assertFileExists($avatarFilePath);

        unlink($avatarFilePath);
    }

    /** @test */
    public function when_model_avatar_field_updated_a_new_avatar_will_be_generated()
    {
        $user = User::Create([
            'name' => 'John Smith',
            'email' => 'user@email.com',
            'password' => 'test',
        ]);

        $updatedUser = User::find($user->id);

        $updatedUser->update([
            'name' => 'Paul Smith',
        ]);

        $this->assertNotEquals($updatedUser->avatar, $user->avatar);

        $avatarFilePath = config('initials-avatar-generator.storage_path').'/';

        $this->assertFileExists($avatarFilePath.$updatedUser->avatar);

        unlink($avatarFilePath.$user->avatar);
        unlink($avatarFilePath.$updatedUser->avatar);
    }
}
