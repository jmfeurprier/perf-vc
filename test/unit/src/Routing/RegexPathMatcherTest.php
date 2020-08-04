<?php

namespace perf\Vc\Routing;

class RegexPathMatcherTest extends \PHPUnit_Framework_TestCase
{

    public function testWithNotMatchingPath()
    {
        $pathMatcher = new RegexPathMatcher('foo');

        $result = $pathMatcher->match('/baz/qux');

        $this->assertInstanceOf(PathMatchingResult::class, $result);
        $this->assertFalse($result->matched());
    }

    public static function dataProviderMatchingCases()
    {
        return array(
            array('foo',       '/foo/bar'),
            array('^foo/bar$', '/foo/bar'),
        );
    }

    /**
     * @dataProvider dataProviderMatchingCases
     */
    public function testWithMatchingPath($pattern, $path)
    {
        $pathMatcher = new RegexPathMatcher($pattern);

        $result = $pathMatcher->match($path);

        $this->assertInstanceOf(PathMatchingResult::class, $result);
        $this->assertTrue($result->matched());
    }
}
