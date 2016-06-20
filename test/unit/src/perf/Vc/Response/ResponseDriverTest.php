<?php

namespace perf\Vc\Response;

/**
 *
 */
class ResponseDriverTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetType()
    {
        $type = 'foo';

        $driver = new ResponseDriver(
            $type
        );

        $result = $driver->getType();

        $this->assertSame($type, $result);
    }

    /**
     *
     */
    public function testGenerateHeaders()
    {
        $type    = 'foo';
        $headers = array(
            'bar' => 'baz',
        );
        $settings = array(
            'qux' => 'abc',
        );

        $driver = new ResponseDriver($type);

        $result = $driver->generateHeaders($headers, $settings);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
    }

    /**
     *
     */
    public function testGenerateContentWithoutContentTransformer()
    {
        $type    = 'foo';
        $content = 'bar';
        $vars    = array(
            'baz' => 'qux',
        );
        $settings = array(
            'abc' => 'def',
        );

        $route = $this->getMockBuilder('perf\\Vc\\Routing\\Route')->disableOriginalConstructor()->getMock();

        $driver = new ResponseDriver($type);

        $result = $driver->generateContent($content, $vars, $settings, $route);

        $this->assertInstanceOf('perf\\Source\\Source', $result);
        $this->assertSame($content, $result->getContent());
    }
}
