# Web Browser [![Build Status][icon-status]][link-status] [![Total Downloads][icon-downloads]][link-downloads] [![MIT License][icon-license]](LICENSE.md)

Programmatically navigate/test the web.

- [Requirements](#requirements)
- [Install](#install)
  - [Install chrome driver](#install-chrome-driver)
  - [Install phantomjs driver](#install-phantomjs-driver)
- [Usage](#usage)
    - [Chrome](#chrome)
    - [Phantomjs](#phantomjs)
- [API](#api)
    - [Example](#example)
    - [Testing](#testing)
        - [PHPUnit](#phpunit)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Requirements

- PHP >= 7.2

## Install

You may install this package using [composer][link-composer].

```shell
$ composer require bhittani/web-browser
```

### Install chrome driver

```shell
$ vendor/bin/install-chrome-driver
```

### Install phantomjs driver

```shell
$ vendor/bin/install-phantomjs-driver
```

## Usage

This package conveniently wraps [laravel/dusk](https://github.com/laravel/dusk).

### Chrome

First ensure you have installed the [chrome driver](#install-chrome-driver).

```php
<?php

use Bhittani\WebBrowser\Chrome;

$browser = new Chrome;
```

### Phantomjs

First ensure you have installed the [phantomjs driver](#install-phantomjs-driver).

```php
<?php

use Bhittani\WebBrowser\Phantomjs;

$browser = new Phantomjs;
```

## API

All browser instances extend `Laravel\Dusk\Browser`, hence, the same API applies.

### Example

```php
<?php

$browser->visit('https://example.com')->assertSee('Example');
```

### Testing

We can also make assertions and capture failures within a testing environment.

```php
<?php

use Bhittani\WebBrowser\Browser;
use Bhittani\WebBrowser\Phantomjs;
use Bhittani\WebBrowser\Testing;

Testing::bootstrap(); // Required for purging failed assertions.

Browser::test(function ($browser1, $browser2/*, ... */) {
    $browser1->visit('https://example.com')->assertSee('Example');
    $browser2->visit('https://example.net')->assertSee('Example');
});

Phantomjs::test(/*...*/);

Testing::tearDown(); // Required for closing all browsers.
```

> The above test callback will capture any failed assertions by taking a screenshot, save the console log and the source of a web page that caused the failure.

#### PHPUnit

A `TestCase` class and a `TestTrait` trait under the `Bhittani\WebBrowser` namespace is included for making it easy to write your browser tests using `PHPUnit`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed.

## Testing

```shell
$ git clone https://github.com/kamalkhan/web-browser

$ cd web-browser

$ composer install

$ composer install-drivers

$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email `shout@bhittani.com` instead of using the issue tracker.

## Credits

- [Kamal Khan](http://bhittani.com)
- [All Contributors](https://github.com/kamalkhan/web-browser/contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

<!--Status-->

[icon-status]: https://img.shields.io/github/workflow/status/kamalkhan/web-browser/main?style=flat-square

[link-status]: https://github.com/kamalkhan/web-browser

<!--Downloads-->

[icon-downloads]: https://img.shields.io/packagist/dt/bhittani/web-browser.svg?style=flat-square

[link-downloads]: https://packagist.org/packages/bhittani/web-browser

<!--License-->

[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

<!--composer-->

[link-composer]: https://getcomposer.org
