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

class Backtrace
{
    public static function caller(int $backtrace = 0): string
    {
        $trace = debug_backtrace(
            DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS,
            $backtrace + 2
        );

        if (count($trace) != ($backtrace + 2)) {
            return 'unknown';
        }

        $calledBy = array_pop($trace);

        if (! $calledBy) {
            return 'unknown';
        }

        $caller = '';

        $caller = static::append($caller, $calledBy['class'] ?? '');
        $caller = static::append($caller, $calledBy['function'] ?? '');

        return $caller ?: 'unknown';
    }

    protected static function append(string $caller, string $subject): string
    {
        return ltrim($caller.'_', '_').str_replace('\\', '_', $subject);
    }
}
