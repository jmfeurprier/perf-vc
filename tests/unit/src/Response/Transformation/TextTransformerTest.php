<?php

namespace perf\Vc\Response\Transformation;

use PHPUnit\Framework\TestCase;

class TextTransformerTest extends TestCase
{
    private TextTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new TextTransformer();
    }

    public function testContentIsNotModified()
    {
        $content = 'some texxt';

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
