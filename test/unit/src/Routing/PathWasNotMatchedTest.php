<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class PathWasNotMatchedTest extends TestCase
{
    public function testMatchedReturnsFalse()
    {
        $pathMatchingResult = new PathWasNotMatched();

        $result = $pathMatchingResult->matched();

        $this->assertFalse($result);
    }

    public function testGetParametersWillThrowException()
    {
        $pathMatchingResult = new PathWasNotMatched();

        $this->expectException(RuntimeException::class);

        $pathMatchingResult->getParameters();
    }
}
