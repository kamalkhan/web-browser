<?php

/*
 * This file is part of bhittani/web-browser.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\WebBrowser;

use Bhittani\WebDriver\Chrome as ChromeDriver;
use Closure;
use Laravel\Dusk\Browser as DuskBrowser;

class Browser extends DuskBrowser
{
    /** @var string */
    public static $defaultDriver = ChromeDriver::class;

    /** @var string */
    protected static $storage;

    /** {@inheritdoc} */
    public function __construct($driver = null, $resolver = null)
    {
        static::ensureStorageIsAvailable();

        parent::__construct($driver ?: (static::$defaultDriver)::make(), $resolver);
    }

    public static function test(Closure $fn): void
    {
        (new Testing(static::class))->browse($fn, 1);
    }

    public static function ensureStorageIsAvailable(): void
    {
        if (! static::$storage) {
            static::storage(getcwd().'/browser');
        }
    }

    public static function defaultDriver(string $class): void
    {
        static::$defaultDriver = $class;
    }

    public static function baseUrl(string $url): void
    {
        static::$baseUrl = rtrim($url, '/');
    }

    public static function storage(string $path): void
    {
        $path = rtrim($path, '\/');

        static::$storage = $path;
        static::$storeSourceAt = $path.'/source';
        static::$storeConsoleLogAt = $path.'/console';
        static::$storeScreenshotsAt = $path.'/screenshots';
    }
}
