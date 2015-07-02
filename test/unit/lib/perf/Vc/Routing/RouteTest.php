<?php

namespace perf\Vc\Routing;

/**
 *
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->address = $this->getMockBuilder('\\perf\\Vc\\Routing\\Address')->disableOriginalConstructor()->getMock();
    }

    /**
     *
     */
    public function testGetAddress()
    {
        $route = $this->buildRoute();

        $result = $route->getAddress();

        $this->assertSame($this->address, $result);
    }

    /**
     *
     */
    public function testGetParametersWithoutParameters()
    {
        $route = $this->buildRoute();

        $result = $route->getParameters();

        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testHasParametersWithNonExistingParameterWillReturnFalse()
    {
        $route = $this->buildRoute();

        $result = $route->hasParameter('foo');

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testHasParametersWithExistingParameterWillReturnTrue()
    {
        $parameters = array(
            'foo' => 'bar',
        );

        $route = $this->buildRoute($parameters);

        $result = $route->hasParameter('foo');

        $this->assertTrue($result);
    }

    /**
     *
     * @expectedException \DomainException
     */
    public function testHasParametersWithNonExistingParameterWillThrowException()
    {
        $route = $this->buildRoute();

        $route->getParameter('foo');
    }

    /**
     *
     */
    public function testGetParametersWithExistingParameterWillReturnExpected()
    {
        $parameters = array(
            'foo' => 'bar',
        );

        $route = $this->buildRoute($parameters);

        $result = $route->getParameter('foo');

        $this->assertSame('bar', $result);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testWithInvalidParameterKeyWillThrowException()
    {
        $parameters = array(
            123 => 'bar',
        );

        $this->buildRoute($parameters);
    }

    /**
     *
     */
    private function buildRoute(array $parameters = array())
    {
        return new Route($this->address, $parameters);
    }
}
