<?php

namespace perf\Vc\Exception;

use PHPUnit\Framework\TestCase;

class ForwardExceptionTest extends TestCase
{
    public function testGetModule()
    {
        $module    = 'foo';
        $action    = 'bar';
        $arguments = [];

        $exception = new ForwardException($module, $action, $arguments);

        $this->assertSame($module, $exception->getModule());
    }

    public function testGetAction()
    {
        $module    = 'foo';
        $action    = 'bar';
        $arguments = [];

        $exception = new ForwardException($module, $action, $arguments);

        $this->assertSame($action, $exception->getAction());
    }

    public function testGetArguments()
    {
        $module    = 'foo';
        $action    = 'bar';
        $arguments = [
            'baz' => 'qux',
        ];

        $exception = new ForwardException($module, $action, $arguments);

        $this->assertSame($arguments, $exception->getArguments());
    }
}
