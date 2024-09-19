# FormRequest helpers using validated data

[![Latest Version on Packagist](https://img.shields.io/packagist/v/soyhuce/laravel-safe-request.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-safe-request)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-safe-request/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/soyhuce/laravel-safe-request/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-safe-request/phpstan.yml?branch=main&label=phpstan&style=flat-square)](https://github.com/soyhuce/laravel-safe-request/actions/workflows/phpstan.yml)
[![GitHub PHPStan Action Status](https://img.shields.io/github/actions/workflow/status/soyhuce/laravel-safe-request/php-cs-fixer.yml?branch=main&label=php-cs-fixer&style=flat-square)](https://github.com/soyhuce/laravel-safe-request/actions/workflows/php-cs-fixer.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/soyhuce/laravel-safe-request.svg?style=flat-square)](https://packagist.org/packages/soyhuce/laravel-safe-request)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require soyhuce/laravel-safe-request
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-safe-request-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-safe-request-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-safe-request-views"
```

## Usage

```php
$laravelSafeRequest = new Soyhuce\LaravelSafeRequest();
echo $laravelSafeRequest->echoPhrase('Hello, Soyhuce!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Bastien Philippe](https://github.com/bastien-phi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
