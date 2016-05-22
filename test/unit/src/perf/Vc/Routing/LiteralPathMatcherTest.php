<?php

namespace perf\Vc\Routing;

/**
 *
 */
class LiteralPathMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testWithMatchingPath()
    {
        $pathMatcher = new LiteralPathMatcher('/foo/bar');

        $result = $pathMatcher->match('/foo/bar');

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatchingResult', $result);
        $this->assertTrue($result->matched());
    }

    /**
     *
     */
    public function testWithNotMatchingPath()
    {
        $pathMatcher = new LiteralPathMatcher('/foo/bar');

        $result = $pathMatcher->match('/baz/qux');

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatchingResult', $result);
        $this->assertFalse($result->matched());
    }
}
