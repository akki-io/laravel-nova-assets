<p align="center">
    <img src="https://raw.githubusercontent.com/akki-io/laravel-nova-assets/master/hero.png" alt="Hero" width="600">
</p>

# Laravel Nova Search

[![Latest Version](https://img.shields.io/github/release/akki-io/laravel-nova-assets.svg?style=flat-square)](https://github.com/akki-io/laravel-nova-assets/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![StyleCI](https://styleci.io/repos/441735142/shield?branch=master)](https://styleci.io/repos/441735142)
[![Total Downloads](https://img.shields.io/packagist/dt/akki-io/laravel-nova-assets.svg?style=flat-square)](https://packagist.org/packages/akki-io/laravel-nova-assets)

This package provides a console command to convert dynamic JS/CSS to static JS/CSS assets.

## Requirements

- laravel-mix v6.0+
- php 7.3+
- laravel nova 3.0+

## Installation

You can install the package via composer:

```bash
composer require akki-io/laravel-nova-assets
```

Publish the package files:

```bash
php artisan vendor:publish --provider 'AkkiIo\LaravelNovaAssets\LaravelNovaAssetsServiceProvider'`
```

This will publish the 
- config file `config/laravel-nova-assets.php` and 
- the webpack file `webpack.mix.nova.js`.

## Usage

To create static assets the package provides a simple console command that will do the job for you.

Run

```bash
php artisan nova:mix
```

Once the command is executed you will need to update the laravel nova auth layout `auth->layout.blade.php` and main layout `layout.blade.php` to use the compiled assets.

- Copy the auth layout `vendor/laravel/nova/resources/views/auth/layout.blade.php` to `resources/views/vendor/nova/auth/layout.blade.php`
- Copy the main layout `vendor/laravel/nova/resources/views/layout.blade.php` to `resources/views/vendor/nova/layout.blade.php`

Add manifest files above the `</head>` tag for both files.

```html
<link rel="manifest" href="/vendor/laravel-nova-assets/mix-manifest.json">
```

Replace these following section in the newly copied files.

Original Content
```php
// Tool Styles
@foreach(\Laravel\Nova\Nova::availableStyles(....
    ....
@endforeach
```

New Content

```html
<link rel="stylesheet" href="{{ mix('tool-styles.css', 'vendor/laravel-nova-assets') }}">
```

---

Original Content

```php
<!-- Theme Styles -->
@foreach(\Laravel\Nova\Nova::themeStyles() ...)
    ....
@endforeach
```

New Content

```html
<link rel="stylesheet" href="{{ mix('theme-styles.css', 'vendor/laravel-nova-assets') }}">
```

---

Original Content

```php
<!-- Tool Scripts -->
@foreach (\Laravel\Nova\Nova::availableScripts(request()) ...)
.....
@endforeach
```

New Content

```html
<script src="{{ mix('tool-scripts.js', 'vendor/laravel-nova-assets') }}"></script>
```

---

If you are using custom scripts and styles, add the following sections to these files accordingly.

```html
<link rel="stylesheet" href="{{ mix('custom-styles.css', 'vendor/laravel-nova-assets') }}">
```
```html
<script src="{{ mix('custom-scripts.js', 'vendor/laravel-nova-assets') }}"></script>
```

**For copyright reason I cannot include  those files in this project.** 

### Adding custom CSS and JSS

You can specify you custom CSS/JS in the config file `laravel-nova-assets.php` under the `styles` and `scripts` section.

### Running the command on a CI/CD

You will need to create a dummy user if you are using this command to generate assets. Unfortunately, I was not able to find a work around for this.

## Using CDN

`publiux/laravelcdn` package provides a simple way to move these assets to CDN for better performance. Refer to the repo here for more information - https://github.com/publiux/laravelcdn

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email hello@akki.io instead of using the issue tracker.

## Credits

- [Akki Khare](https://github.com/akki-io)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
