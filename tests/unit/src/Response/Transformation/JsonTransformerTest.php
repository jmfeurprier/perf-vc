<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\HeaderCollection;
use PHPUnit\Framework\TestCase;

class JsonTransformerTest extends TestCase
{
    private JsonTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new JsonTransformer();
    }

    public function testContentIsNotModified(): void
    {
        $content = [
            'foo' => 'bar',
        ];

        $result = $this->transformer->transformContent($content, [], []);

        $this->assertIsString($result);
        $this->assertJson($result);
    }

    public function testHeadersAreAddedWithDefaultCharset(): void
    {
        $headers = new HeaderCollection();

        $result = $this->transformer->transformHeaders($headers, [], []);

        $this->assertCount(1, $result->getAll());
    }
}
