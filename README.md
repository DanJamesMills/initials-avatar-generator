# Intro

[![Latest Version on Packagist](https://img.shields.io/packagist/v/danjamesmills/initials-avatar-generator.svg?style=flat-square)](https://packagist.org/packages/danjamesmills/initials-avatar-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/danjamesmills/initials-avatar-generator.svg?style=flat-square)](https://packagist.org/packages/danjamesmills/initials-avatar-generator)
![GitHub Actions](https://github.com/danjamesmills/initials-avatar-generator/actions/workflows/main.yml/badge.svg)

Introducing the Laravel Avatar package, a powerful tool for generating unique avatar images for any model in your Laravel application. This package allows you to easily create unique avatar images based on fields you specify for your models, such as name, email, or any other field. With this package, you can easily add avatar functionality to your application and ensure that each user or model has a distinct and personalised avatar image. It's also configurable to use different image generation libraries, so you can pick the one that fits your project best. With a simple and intuitive API, you can easily generate avatars for any model in your application, saving you time and effort. The Laravel Avatar package makes it easy to add personalized avatars to your application, enhance the user experience and make your app look more professional.

## Installation

You can install the package via composer:

```bash
composer require danjamesmills/initials-avatar-generator
```

## Publish Config (optional)

You should publish the migration and the config/initials-avatar-generator.php config file with:

```php
php artisan vendor:publish --provider="DanJamesMills\InitialsAvatarGenerator\InitialsAvatarGeneratorServiceProvider"
```

Run the migrations: After the config and migration have been published and configured, you can create the tables for this package by running:

```php
php artisan migrate
```

## Publicly Accessible Storage Path

In the config/initials-avatar-generator.php we have used the following path below for storage of avatar images, feel free to change this.

```php
'storage_path' => storage_path('app/public/avatars/'),
```

If your using the above don't forget to link storage and public paths from console and create 'avatars' folder.

```php
mkdir storage/app/public/avatars
```

```php
php artisan storage:link
```

## Basic Usage

First, add the DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar trait to your User model(s):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar;

class User extends Authenticatable
{
    use HasAvatar;

    // ...
}
```

### Defining An Accessor

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /**
     * Get the user's avatar.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => url("/storage/avatars/{$value}"),
        );
    }
}
```

### Define Model Avatar Fields

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar;

class User extends Authenticatable
{
    /**
     * Used to define the fields of which initials avatar 
     * generator will create initials from.
     *
     * @return string
     */
    protected function defineNameInitialsAvatarGenerator(): string
    {
        return $this->name;
    }
}
```

If you have used a different column name from the default migration name of 'avatar', 
you must define this new column name on the model.

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use DanJamesMills\InitialsAvatarGenerator\Traits\HasAvatar;

class User extends Authenticatable
{
    /**
     * Used to define the column field name of which 
     * you want to save avatar image to.
     *
     * @return string
     */
    protected function defineAvatarColumnName(): string
    {
        return 'avatarColumnName';
    }
}
```

### Generating Avatar

If your model has no avatar set, you can generate one below.

```php
$user = User::findOrFail(4);

$user->generateAvatarAndSet();
$user->save();

return "<img src='{$user->avatar}' width='250px' />";
```

If you update the value of the avatar field i.e user's name, an avatar will be automatically generated and saved for you.

```php
$user = User::findOrFail(4);

$user->name = 'John Doe'; // changed from Dan Doe
$user->save();

return "<img src='{$user->avatar}' width='250px' />";
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email daniel620@hotmail.co.uk instead of using the issue tracker.

## Credits

-   [Danny Mills](https://github.com/danjamesmills)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
