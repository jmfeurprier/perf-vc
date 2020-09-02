<?php

namespace perf\Vc\Response\Transformation;

use PHPUnit\Framework\TestCase;

class XmlTransformerTest extends TestCase
{
    private XmlTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new XmlTransformer();
    }

    public function testContentIsNotModified()
    {
        $content = '<xml />';

        $result = $this->transformer->transformContent($content, [], []);

        $this->assertSame($content, $result);
    }

    public function testHeadersAreAddedWithDefaultCharset()
    {
        $headers = [];

        $result = $this->transformer->transformHeaders($headers, [], []);

        $this->assertCount(1, $result);
    }
}
