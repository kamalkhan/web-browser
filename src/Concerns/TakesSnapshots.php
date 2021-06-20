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
    /** @var int|string */
    public function snapshot($slug, bool $overwrite = false): self
    {
        $name = rtrim($this->getName().'_'.$slug, '_');

        $filename = 'snapshot-'.$name;
        $filepath = rtrim(Browser::$storeScreenshotsAt, '\/').'/'.$filename.'.png';

        if (! is_file($filepath) || $overwrite) {
            $this->screenshot($filename);

            throw new WarningException("Snapshot created for '{$name}'.");

            return $this;
        }

        $tempname = 'failure-'.$name;
        $temppath = rtrim(Browser::$storeScreenshotsAt, '\/').'/'.$tempname.'.png';

        $this->screenshot($tempname);

        try {
            Assert::assertEquals(md5_file($temppath), md5_file($filepath));
            unlink($temppath);
        } catch (ExpectationFailedException $e) {
            Assert::fail("Snapshot mismatch for '{$name}'.");
        }

        return $this;
    }
}
