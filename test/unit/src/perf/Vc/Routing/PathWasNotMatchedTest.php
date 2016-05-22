<?php

namespace perf\Vc\Routing;

/**
 *
 */
class PathWasNotMatchedTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testMatchedReturnsFalse()
    {
        $pathMatchingResult = new PathWasNotMatched();

        $result = $pathMatchingResult->matched();

        $this->assertFalse($result);
    }

    /**
     *
     * @expectedException \RuntimeException
     */
    public function testGetParametersWillThrowException()
    {
        $pathMatchingResult = new PathWasNotMatched();

        $pathMatchingResult->getParameters();
    }
}
