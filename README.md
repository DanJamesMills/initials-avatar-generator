# Intro

[![Latest Version on Packagist](https://img.shields.io/packagist/v/danjamesmills/initials-avatar-generator.svg?style=flat-square)](https://packagist.org/packages/danjamesmills/initials-avatar-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/danjamesmills/initials-avatar-generator.svg?style=flat-square)](https://packagist.org/packages/danjamesmills/initials-avatar-generator)
![GitHub Actions](https://github.com/danjamesmills/initials-avatar-generator/actions/workflows/main.yml/badge.svg)

Display unique avatar image for any models based on their fields you specify.

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
    private function defineNameInitialsAvatarGenerator(): string
    {
        return $this->name;
    }
}
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