<?php

namespace perf\Vc\Response\Transformation;

use PHPUnit\Framework\TestCase;

class HtmlTransformerTest extends TestCase
{
    private HtmlTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new HtmlTransformer();
    }

    public function testContentIsNotModified()
    {
        $content = '<html />';

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
