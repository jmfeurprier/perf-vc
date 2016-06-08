<?php

namespace perf\Vc\Routing;

/**
 *
 */
class PathMatcherTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testWithNotMatchingPath()
    {
        $pathMatcher = new PathMatcher('foo');

        $result = $pathMatcher->match('/baz/qux');

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatchingResult', $result);
        $this->assertFalse($result->matched());
    }

    /**
     *
     */
    public static function dataProviderMatchingCases()
    {
        return array(
            array('foo/bar',    '/foo/bar', array()),
            array('/foo/bar',   '/foo/bar', array()),
            array('/foo/{bar}', '/foo/123', array('bar' => '123')),
            array('/foo/{bar}', '/foo/baz', array('bar' => 'baz')),
        );
    }

    /**
     *
     * @dataProvider dataProviderMatchingCases
     */
    public function testWithMatchingPath($pattern, $path, array $parametersExpected)
    {
        $pathMatcher = new PathMatcher($pattern);

        $result = $pathMatcher->match($path);

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatchingResult', $result);
        $this->assertTrue($result->matched());

        $parametersResult = $result->getParameters();
        $this->assertCount(count($parametersExpected), $parametersResult);
        foreach ($parametersExpected as $key => $value) {
            $this->assertArrayHasKey($key, $parametersResult);
            $this->assertSame($value, $parametersResult[$key]);
        }
    }
}
