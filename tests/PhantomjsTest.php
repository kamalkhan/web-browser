<?php

namespace Bhittani\WebBrowser;

use PHPUnit\Framework\TestCase;

class PhantomjsTest extends TestCase
{
    /** @test */
    function it_can_browse_the_web_and_make_assertions_using_phantomjs()
    {
        $browser = new Phantomjs;
        $browser->visit('https://example.com')->assertSee('Example');
    }
}
