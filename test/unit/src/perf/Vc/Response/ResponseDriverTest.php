<?php

namespace perf\Vc\Response;

use PHPUnit\Framework\TestCase;

class ResponseDriverTest extends TestCase
{
    public function testGetType()
    {
        $type = 'foo';

        $driver = new ResponseDriver(
            $type
        );

        $result = $driver->getType();

        $this->assertSame($type, $result);
    }

    public function testGenerateHeaders()
    {
        $type     = 'foo';
        $headers  = [
            'bar' => 'baz',
        ];
        $settings = [
            'qux' => 'abc',
        ];

        $driver = new ResponseDriver($type);

        $result = $driver->generateHeaders($headers, $settings);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testGenerateContentWithoutContentTransformer()
    {
        $type     = 'foo';
        $content  = 'bar';
        $vars     = [
            'baz' => 'qux',
        ];
        $settings = [
            'abc' => 'def',
        ];

        $route = $this->createMock('perf\\Vc\\Routing\\Route');

        $driver = new ResponseDriver($type);

        $result = $driver->generateContent($content, $vars, $settings, $route);

        $this->assertInstanceOf('perf\\Source\\Source', $result);
        $this->assertSame($content, $result->getContent());
    }
}
