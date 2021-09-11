<?php

namespace perf\Vc;

use PHPUnit\Framework\TestCase;

class ForwardExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->route = $this->createMock('perf\\Vc\\Routing\\Route');

        $this->exception = new ForwardException($this->route);
    }

    public function testGetRoute()
    {
        $this->assertSame($this->route, $this->exception->getRoute());
    }
}
