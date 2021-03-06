<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

class ArgumentDefinitionTest extends TestCase
{
    public function testGetName()
    {
        $name         = 'foo';
        $format       = 'bar';
        $defaultValue = 'baz';

        $argumentDefinition = new ArgumentDefinition(
            $name,
            $format,
            $defaultValue
        );

        $this->assertSame($name, $argumentDefinition->getName());
    }

    public function testGetFormat()
    {
        $name         = 'foo';
        $format       = 'bar';
        $defaultValue = 'baz';

        $argumentDefinition = new ArgumentDefinition(
            $name,
            $format,
            $defaultValue
        );

        $this->assertSame($format, $argumentDefinition->getFormat());
    }

    public function testGetDefaultValue()
    {
        $name         = 'foo';
        $format       = 'bar';
        $defaultValue = 'baz';

        $argumentDefinition = new ArgumentDefinition(
            $name,
            $format,
            $defaultValue
        );

        $this->assertSame($defaultValue, $argumentDefinition->getDefaultValue());
    }
}
