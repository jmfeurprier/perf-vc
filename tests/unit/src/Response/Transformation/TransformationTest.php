<?php

namespace perf\Vc\Response\Transformation;

use PHPUnit\Framework\TestCase;

class TransformationTest extends TestCase
{
    public function testGetTransformer(): void
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $transformation = new Transformation($transformer);

        $this->assertSame($transformer, $transformation->getTransformer());
    }

    public function testGetParametersWithParameters(): void
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $parameters = [
            'foo' => 'bar',
        ];

        $transformation = new Transformation($transformer, $parameters);

        $this->assertSame($parameters, $transformation->getParameters());
    }

    public function testGetParametersWithoutParameters(): void
    {
        $transformer = $this->createMock(TransformerInterface::class);

        $transformation = new Transformation($transformer);

        $this->assertEmpty($transformation->getParameters());
    }
}
