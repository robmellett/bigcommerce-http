# A Saloon-based HTTP client for the BigCommerce API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/robmellett/bigcommerce-http.svg?style=flat-square)](https://packagist.org/packages/robmellett/bigcommerce-http)
[![GitHub Tests Action Status](https://github.com/robmellett/bigcommerce-http/actions/workflows/run-tests.yml/badge.svg)](https://github.com/robmellett/bigcommerce-http/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://github.com/robmellett/bigcommerce-http/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/robmellett/bigcommerce-http/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/robmellett/bigcommerce-http.svg?style=flat-square)](https://packagist.org/packages/robmellett/bigcommerce-http)

A Saloon-based HTTP client for the BigCommerce API.

## Installation

You can install the package via composer:

```bash
composer require robmellett/bigcommerce-http
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="bigcommerce-http-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="bigcommerce-http-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="bigcommerce-http-views"
```

## Usage

```php
$bigcommerceHttp = new RobMellett\BigcommerceHttp();
echo $bigcommerceHttp->echoPhrase('Hello, RobMellett!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Rob Mellett](https://github.com/robmellett)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
