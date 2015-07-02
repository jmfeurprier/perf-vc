<?php

namespace perf\Vc;

/**
 *
 */
class HtmlEscaperTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public static function dataProviderCharacters()
    {
        return array(
            array('foo', 'foo'),
            array('\'', '&#039;'),
            array('"', '&quot;'),
            array('<', '&lt;'),
            array('>', '&gt;'),
            array('&', '&amp;'),
        );
    }

    /**
     *
     * @dataProvider dataProviderCharacters
     */
    public function testEscape($content, $escapedContent)
    {
        $escaper = new HtmlEscaper();

        $result = $escaper->escape($content);

        $this->assertSame($escapedContent, $result);
    }
}
