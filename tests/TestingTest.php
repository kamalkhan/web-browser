<?php

namespace Bhittani\WebBrowser;

use PHPUnit\Framework\ExpectationFailedException;

class TestingTest extends TestCase
{
    /** @test */
    function it_can_test_drive_the_web()
    {
        $name = 'failure-'.str_replace('\\', '_', static::class).'_'.__FUNCTION__.'-0.png';
        $filepath = __DIR__.'/../browser/screenshots/'.$name;

        try {
            Browser::test(function ($browser) {
                $browser->visit('https://example.com')->assertSee('404');
            });
        } catch (ExpectationFailedException $e) {
            $this->assertEquals(
                'Did not see expected text [404] within element [body].'
                .PHP_EOL.'Failed asserting that false is true.',
                $e->getMessage()
            );

            return $this->assertFileExists($filepath);
        } finally {
            if (is_file($filepath)) {
                @unlink($filepath);
            }
        }

        $this->fail(
            "Did not expect to see text '404' within the body element."
            .PHP_EOL.'Expected a failure screenshot but did not capture one.'
        );
    }
}
