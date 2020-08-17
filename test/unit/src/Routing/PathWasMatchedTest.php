<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

class PathWasMatchedTest extends TestCase
{
    public function testMatchedReturnsTrue()
    {
        $pathMatchingResult = new PathWasMatched();

        $result = $pathMatchingResult->matched();

        $this->assertTrue($result);
    }

    public function testGetParametersWillReturnExpected()
    {
        $parameters = array(
            'foo' => 'bar',
        );

        $pathMatchingResult = new PathWasMatched($parameters);

        $result = $pathMatchingResult->getParameters();

        $this->assertSame($parameters, $result);
    }
}
