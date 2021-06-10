<?php

namespace Bhittani\WebBrowser;

use PHPUnit\Framework\TestCase;

class ChromeTest extends TestCase
{
    /** @test */
    function it_can_browse_the_web_and_make_assertions_using_chrome()
    {
        $browser = new Chrome;
        $browser->visit('https://example.com')->assertSee('Example');
    }
}
