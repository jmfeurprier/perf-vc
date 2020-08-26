<?php

namespace perf\Vc;

use perf\Vc\Exception\ForwardException;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouteInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ForwardExceptionTest extends TestCase
{
    /**
     * @var RouteInterface|MockObject
     */
    private $route;

    private ForwardException $exception;

    public function testGetRoute()
    {
        $this->assertSame($this->route, $this->exception->getRoute());
    }

    protected function setUp(): void
    {
        $this->route = $this->createMock(RouteInterface::class);

        $this->exception = new ForwardException($this->route);
    }
}
