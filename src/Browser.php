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

use Bhittani\WebBrowser\Concerns\TakesSnapshots;
use Bhittani\WebDriver\Chrome as ChromeDriver;
use Bhittani\WebDriver\Payload\Contract as PayloadContract;
use Closure;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser as DuskBrowser;

class Browser extends DuskBrowser
{
    use TakesSnapshots;

    /** @var string */
    public static $defaultDriver = ChromeDriver::class;

    /** @var array */
    public static $defaultOptions = [];

    /** @var arrray */
    protected static $initCallbacks = [];

    /** @var string */
    protected static $storage;

    /** @var string */
    protected $name;

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

        foreach (static::$initCallbacks as $fn) {
            $fn();
        }

        $this->name($this->name ?: Backtrace::caller(1));
    }

    public static function initUsing(callable $fn): void
    {
        static::$initCallbacks[] = $fn;
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

    public static function defaultOptions(array $options): void
    {
        static::$defaultOptions = $options;
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

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
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
        if (is_array($options)) {
            $options += static::$defaultOptions;
        }

        return $class::make(null, $options);
    }
}
