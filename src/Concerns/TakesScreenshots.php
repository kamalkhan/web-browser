<?php

/*
 * This file is part of bhittani/web-browser.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\WebBrowser\Concerns;

use Bhittani\WebBrowser\Browser;

trait TakesScreenshots
{
    /**
     * @param string $name
     * @param string|null $selector
     *
     * @return self
     */
    public function screenshot($name, $selector = null)
    {
        if (! $selector) {
            return parent::screenshot($name);
        }

        $filepath = rtrim(Browser::$storeScreenshotsAt, '\/').'/'.$name.'.png';

        $tempname = 'temp-'.$name;
        $temppath = rtrim(Browser::$storeScreenshotsAt, '\/').'/'.$tempname.'.png';

        parent::screenshot($tempname);

        $element = $this->element($selector);

        $w = $element->getSize()->getWidth();
        $h = $element->getSize()->getHeight();
        $x = $element->getLocationOnScreenOnceScrolledIntoView()->getX();
        $y = $element->getLocationOnScreenOnceScrolledIntoView()->getY();

        $img = imagecreatetruecolor($w, $h);
        $src = imagecreatefrompng($temppath);

        imagecopy(
            $img,
            $src,
            0, 0,
            (int) ceil($x), (int) ceil($y),
            (int) ceil($w), (int) ceil($h)
        );

        imagepng($img, $filepath);

        unlink($temppath);

        return $this;
    }
}
