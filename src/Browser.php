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
use Bhittani\WebDriver\Payload\Contract as PayloadContract;
use Closure;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser as DuskBrowser;

class Browser extends DuskBrowser
{
    /** @var string */
    public static $defaultDriver = ChromeDriver::class;

    /** @var string */
    protected static $storage;

    /**
     * {@inheritdoc}
     *
     * @param array|PayloadContract|RemoteWebDriver $driverOrOptions
     */
    public function __construct($driverOrOptions = [], $resolver = null)
    {
        static::ensureStorageIsAvailable();

        [$driver, $options] = static::resolveDriverOrOptions($driverOrOptions);

        parent::__construct($driver ?: static::makeDriver(static::$defaultDriver, $options), $resolver);
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

    /** @param array|PayloadContract|RemoteWebDriver $driverOrOptions */
    protected static function resolveDriverOrOptions($driverOrOptions): array
    {
        $options = [];
        $driver = $driverOrOptions;

        if (! $driverOrOptions instanceof RemoteWebDriver) {
            $driver = null;

            $options = $driverOrOptions instanceof PayloadContract
                ? $driverOrOptions
                : (array) $driverOrOptions;
        }

        return [$driver, $options];
    }

    /** @param array|PayloadContract $options */
    protected static function makeDriver(string $class, $options = []): RemoteWebDriver
    {
        return $class::make(null, $options);
    }
}
