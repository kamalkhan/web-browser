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

    /** @var string */
    protected $callerName = 'unknown';

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
        $trace = debug_backtrace(
            DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS,
            $backtrace + 2
        );

        $calledBy = array_pop($trace);

        if ($calledBy) {
            $this->callerName = str_replace('\\', '_', $calledBy['class']).'_'.$calledBy['function'];
        }

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

        return new $class($driver);
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
