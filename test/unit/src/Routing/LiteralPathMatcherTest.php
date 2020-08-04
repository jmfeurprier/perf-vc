<?php

namespace perf\Vc\Routing;

class LiteralPathMatcherTest extends \PHPUnit_Framework_TestCase
{
    public function testWithMatchingPath()
    {
        $pathMatcher = new LiteralPathMatcher('/foo/bar');

        $result = $pathMatcher->match('/foo/bar');

        $this->assertInstanceOf(PathMatchingResult::class, $result);
        $this->assertTrue($result->matched());
    }

    public function testWithNotMatchingPath()
    {
        $pathMatcher = new LiteralPathMatcher('/foo/bar');

        $result = $pathMatcher->match('/baz/qux');

        $this->assertInstanceOf(PathMatchingResult::class, $result);
        $this->assertFalse($result->matched());
    }
}
