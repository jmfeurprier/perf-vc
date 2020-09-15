<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Exception\VcException;
use PHPUnit\Framework\TestCase;

class TransformerRepositoryTest extends TestCase
{
    public function testEmptyRepository()
    {
        $transformerRepository = new TransformerRepository(
            [
            ]
        );

        $this->expectException(VcException::class);

        $transformerRepository->get(HtmlTransformer::class);
    }

    public function testNonEmptyRepository()
    {
        $transformer           = new HtmlTransformer();
        $transformerRepository = new TransformerRepository(
            [
                $transformer,
            ]
        );

        $result = $transformerRepository->get(HtmlTransformer::class);

        $this->assertSame($transformer, $result);
    }
}
