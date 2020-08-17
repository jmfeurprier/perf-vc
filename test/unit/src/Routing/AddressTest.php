<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    public function testGetModule()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new Address($module, $action);

        $result = $address->getModule();

        $this->assertSame($module, $result);
    }

    public function testGetAction()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new Address($module, $action);

        $result = $address->getAction();

        $this->assertSame($action, $result);
    }

    public function testAddressCastedAsString()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new Address($module, $action);

        $result = (string) $address;

        $this->assertContains($module, $result);
        $this->assertContains($action, $result);
    }
}
