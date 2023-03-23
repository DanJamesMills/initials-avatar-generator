<?php

namespace DanJamesMills\InitialsAvatarGenerator\Tests;

use DanJamesMills\InitialsAvatarGenerator\InitialsAvatarGeneratorServiceProvider;
use Illuminate\Foundation\Auth\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            InitialsAvatarGeneratorServiceProvider::class,
        ];
    }

	/**
	 * Define database migrations.
	 *
	 * @return void
	 */
	protected function defineDatabaseMigrations()
	{
		$this->loadLaravelMigrations();
	}

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('initials-avatar-generator.user_model', \Illuminate\Foundation\Auth\User::class);
        $app['config']->set('initials-avatar-generator.storage_path', __DIR__ .'/');

        $app['config']->set('app.key', 'base64:BHUhusiFkxF97awGsMLp7t8dyjjackvOygnmL7fTDoQ=');
    }
}
