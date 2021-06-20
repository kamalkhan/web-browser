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

trait CanBePaused
{
    /**
     * Pause the browser.
     *
     * @param int $milliseconds
     *
     * @return $this
     */
    public function pause($milliseconds = 0)
    {
        if ($milliseconds > 0) {
            return parent::pause($milliseconds);
        }

        fwrite(STDOUT, "\033[s \033[93m[PAUSED] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");

        do {
        } while (fgets(STDIN, 1024) == '');

        fwrite(STDOUT, "\033[u");

        return $this;
    }
}
