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

trait TestTrait
{
    /** @beforeClass */
    public static function bootstrapWebBrowsers()
    {
        Testing::bootstrap();
    }

    /** @afterClass */
    public static function tearDownWebBrowsers()
    {
        Testing::tearDown();
    }
}
