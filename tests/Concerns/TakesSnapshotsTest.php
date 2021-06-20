<?php

namespace Bhittani\WebBrowser\Concerns;

use Bhittani\WebBrowser\Browser;
use PHPUnit\Framework\TestCase;

class TakesSnapshotsTest extends TestCase
{
    /** @test */
    function it_allows_snapshot_testing()
    {
        $browser = new Browser;

        $browser->visit('https://example.com')
            ->snapshot('', 'div')
            ->quit();
    }
}
