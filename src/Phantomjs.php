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

use Bhittani\WebDriver\Phantomjs as Driver;

class Phantomjs extends Browser
{
    /** {@inheritdoc} */
    public function __construct($driverOrOptions = [], $resolver = null)
    {
        [$driver, $options] = static::resolveDriverOrOptions($driverOrOptions);
        parent::__construct($driver ?: static::makeDriver(Driver::class, $options), $resolver);
    }
}
