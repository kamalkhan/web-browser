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
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Warning as WarningException;

trait TakesSnapshots
{
    /**
     * @param int|string $slug
     * @param string|bool $selectorOrOverwrite
     */
    public function snapshot($slug, $selectorOrOverwrite = false, bool $overwrite = false): self
    {
        $selector = null;

        if (is_bool($selectorOrOverwrite)) {
            $overwrite = $selectorOrOverwrite;
        } else {
            $selector = $selectorOrOverwrite;
        }

        $name = rtrim($this->getName().'_'.$slug, '_');

        $filename = 'snapshot-'.$name;
        $filepath = rtrim(Browser::$storeScreenshotsAt, '\/').'/'.$filename.'.png';

        if (! is_file($filepath) || $overwrite) {
            $this->screenshot($filename, $selector);

            throw new WarningException("Snapshot created for '{$name}'.");

            return $this;
        }

        $tempname = 'temp-'.$name;
        $temppath = rtrim(Browser::$storeScreenshotsAt, '\/').'/'.$tempname.'.png';

        $this->screenshot($tempname, $selector);

        try {
            Assert::assertEquals(md5_file($temppath), md5_file($filepath));
        } catch (ExpectationFailedException $e) {
            Assert::fail("Snapshot mismatch for '{$name}'.");
        } finally {
            unlink($temppath);
        }

        return $this;
    }
}
