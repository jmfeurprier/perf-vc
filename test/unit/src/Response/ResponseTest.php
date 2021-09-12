<?php

namespace perf\Vc\Response;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetContent()
    {
        $headers = [];
        $content = 'foo';

        $contentSource = $this->createMock('perf\\Source\\Source');
        $contentSource->expects($this->atLeastOnce())->method('getContent')->willReturn($content);

        $response = new Response($headers, $contentSource);

        $this->assertSame($content, $response->getContent());
    }

    public function testGetHeadersWithoutHeaders()
    {
        $headers = [];

        $contentSource = $this->createMock('perf\\Source\\Source');

        $response = new Response($headers, $contentSource);

        $this->assertEmpty($response->getHeaders());
    }
}
