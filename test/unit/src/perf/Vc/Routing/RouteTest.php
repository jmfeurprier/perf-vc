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
        $this->address = $this->getMockBuilder('perf\\Vc\\ControllerAddress')->disableOriginalConstructor()->getMock();
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
    public function testGetArgumentsWithoutArguments()
    {
        $route = $this->buildRoute();

        $result = $route->getArguments();

        $this->assertCount(0, $result);
    }

    /**
     *
     */
    public function testHasArgumentsWithNonExistingArgumentWillReturnFalse()
    {
        $route = $this->buildRoute();

        $result = $route->hasArgument('foo');

        $this->assertFalse($result);
    }

    /**
     *
     */
    public function testHasArgumentsWithExistingArgumentWillReturnTrue()
    {
        $arguments = array(
            'foo' => 'bar',
        );

        $route = $this->buildRoute($arguments);

        $result = $route->hasArgument('foo');

        $this->assertTrue($result);
    }

    /**
     *
     * @expectedException \DomainException
     */
    public function testHasArgumentsWithNonExistingArgumentWillThrowException()
    {
        $route = $this->buildRoute();

        $route->getArgument('foo');
    }

    /**
     *
     */
    public function testGetArgumentsWithExistingArgumentWillReturnExpected()
    {
        $arguments = array(
            'foo' => 'bar',
        );

        $route = $this->buildRoute($arguments);

        $result = $route->getArgument('foo');

        $this->assertSame('bar', $result);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     */
    public function testWithInvalidArgumentKeyWillThrowException()
    {
        $arguments = array(
            123 => 'bar',
        );

        $this->buildRoute($arguments);
    }

    /**
     *
     */
    private function buildRoute(array $arguments = array())
    {
        return new Route($this->address, $arguments);
    }
}
