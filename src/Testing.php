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

use Closure;
use Laravel\Dusk\Concerns\ProvidesBrowser;

class Testing
{
    use ProvidesBrowser {
        browse as browseTrait;
    }

    /** @var string */
    protected $browser;

    /** @var int */
    protected $backtrace = 1;

    /** @var string */
    protected $callerName;

    public function __construct(string $browser = null)
    {
        $this->browser = $browser ?: Browser::class;
    }

    public static function tearDown(): void
    {
        static::tearDownDuskClass();
    }

    public static function bootstrap(string $path = null): void
    {
        if ($path) {
            Browser::storage($path);
        }

        Browser::ensureStorageIsAvailable();

        static::createSourceDirectory();
        static::createConsoleDirectory();
        static::createScreenshotsDirectory();

        static::purgeSourceLogs();
        static::purgeConsoleLogs();
        static::purgeScreenshots();
    }

    /** {@inheritdoc} */
    public function browse(Closure $callback, $backtrace = 0)
    {
        $this->backtrace = $backtrace + 1;
        $this->callerName = Backtrace::caller($backtrace + 1);

        return $this->browseTrait($callback);
    }

    /** {@inheritdoc} */
    protected function getCallerName()
    {
        return $this->callerName;
    }

    /** {@inheritdoc} */
    protected function newBrowser($driver)
    {
        $class = $this->browser;

        return (new $class($driver))->name(Backtrace::caller($this->backtrace + 3));
    }

    /** {@inheritdoc} */
    protected function driver()
    {
        // Ignored.
    }

    protected static function createScreenshotsDirectory(): void
    {
        if (! is_dir(Browser::$storeScreenshotsAt)) {
            mkdir(Browser::$storeScreenshotsAt, 0755, true);

            file_put_contents(
                Browser::$storeScreenshotsAt.'/.gitignore',
                'failure-*'.PHP_EOL
            );
        }
    }

    protected static function createConsoleDirectory(): void
    {
        if (! is_dir(Browser::$storeConsoleLogAt)) {
            mkdir(Browser::$storeConsoleLogAt, 0755, true);

            file_put_contents(
                Browser::$storeConsoleLogAt.'/.gitignore',
                '*'.PHP_EOL.'!.gitignore'.PHP_EOL
            );
        }
    }

    protected static function createSourceDirectory(): void
    {
        if (! is_dir(Browser::$storeSourceAt)) {
            mkdir(Browser::$storeSourceAt, 0755, true);

            file_put_contents(
                Browser::$storeSourceAt.'/.gitignore',
                '*'.PHP_EOL.'!.gitignore'.PHP_EOL
            );
        }
    }

    protected static function purgeScreenshots(): void
    {
        static::purgeDebuggingFiles(
            Browser::$storeScreenshotsAt, '/^failure-/'
        );
    }

    protected static function purgeConsoleLogs(): void
    {
        static::purgeDebuggingFiles(
            Browser::$storeConsoleLogAt, '/\.log$/'
        );
    }

    protected static function purgeSourceLogs(): void
    {
        static::purgeDebuggingFiles(
            Browser::$storeSourceAt, '/\.txt$/'
        );
    }

    protected static function purgeDebuggingFiles(string $path, string $regex): void
    {
        $path = rtrim($path, '\/');

        if (! is_dir($path)) {
            return;
        }

        $files = array_values(array_filter(
            array_diff(scandir($path), ['.', '..']),
            function ($file) use ($regex) {
                return preg_match($regex, $file);
            }
        ));

        foreach ($files as $file) {
            @unlink($path.'/'.$file);
        }
    }
}
