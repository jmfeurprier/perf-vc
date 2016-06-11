<?php

namespace perf\Vc;

/**
 *
 */
class ContextTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->request = $this->getMockBuilder('perf\\Vc\\Request')->disableOriginalConstructor()->getMock();

        $this->route = $this->getMockBuilder('perf\\Vc\\Routing\\Route')->disableOriginalConstructor()->getMock();

        $this->context = new Context($this->request, $this->route);
    }

    /**
     *
     */
    public function testGetRequest()
    {
        $this->assertSame($this->request, $this->context->getRequest());
    }

    /**
     *
     */
    public function testGetRoute()
    {
        $this->assertSame($this->route, $this->context->getRoute());
    }
}
