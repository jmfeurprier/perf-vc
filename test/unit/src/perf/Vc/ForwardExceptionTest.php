<?php

namespace perf\Vc;

/**
 *
 */
class ForwardExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->route = $this->getMockBuilder('perf\\Vc\\Routing\\Route')->disableOriginalConstructor()->getMock();

        $this->exception = new ForwardException($this->route);
    }

    /**
     *
     */
    public function testGetRoute()
    {
        $this->assertSame($this->route, $this->exception->getRoute());
    }
}
