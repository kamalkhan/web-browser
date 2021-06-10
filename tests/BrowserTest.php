<?php

namespace Bhittani\WebBrowser;

use PHPUnit\Framework\TestCase;

class BrowserTest extends TestCase
{
    /** @test */
    function it_can_browse_the_web_and_make_assertions()
    {
        $browser = new Browser;
        $browser->visit('https://example.com')->assertSee('Example');
    }
}
