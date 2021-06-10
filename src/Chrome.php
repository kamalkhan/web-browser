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

use Bhittani\WebDriver\Chrome as Driver;

class Chrome extends Browser
{
    public function __construct($driver = null, $resolver = null)
    {
        parent::__construct($driver ?: Driver::make(), $resolver);
    }
}
