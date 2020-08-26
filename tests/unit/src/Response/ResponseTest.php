<?php

namespace perf\Vc\Response;

use perf\Source\SourceInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testGetContent()
    {
        $headers = [];
        $content = 'foo';

        $contentSource = $this->createMock(SourceInterface::class);
        $contentSource->expects($this->atLeastOnce())->method('getContent')->willReturn($content);

        $response = new Response($headers, $contentSource);

        $this->assertSame($content, $response->getContent());
    }

    public function testGetHeadersWithoutHeaders()
    {
        $headers = [];

        $contentSource = $this->createMock(SourceInterface::class);

        $response = new Response($headers, $contentSource);

        $this->assertEmpty($response->getHeaders());
    }
}
