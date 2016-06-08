<?php

namespace perf\Vc\Routing;

/**
 *
 */
class PathPatternParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testParseWithEmptyPattern()
    {
        $parser = new PathPatternParser();

        $result = $parser->parse('', array());

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatcher', $result);
//        $this->assertFalse($result->matched());
    }

    /**
     *
     */
    public function testParseWithSimplePattern()
    {
        $parser = new PathPatternParser();

        $result = $parser->parse('foo', array());

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatcher', $result);
//        $this->assertFalse($result->matched());
    }

    /**
     *
     */
    public function testParseWithOneParameter()
    {
        $parser = new PathPatternParser();

        $result = $parser->parse('foo/{bar}/baz', array());

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatcher', $result);
//        $this->assertFalse($result->matched());
    }

    /**
     *
     */
    public function testParseWithManyParameters()
    {
        $parser = new PathPatternParser();

        $result = $parser->parse('foo/{bar}/{baz}/qux', array());

        $this->assertInstanceOf('\\perf\\Vc\\Routing\\PathMatcher', $result);
//        $this->assertFalse($result->matched());
    }
}
