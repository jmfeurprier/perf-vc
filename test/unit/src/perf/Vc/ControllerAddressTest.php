<?php

namespace perf\Vc;

/**
 *
 */
class ControllerControllerAddressTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetModule()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new ControllerAddress($module, $action);

        $result = $address->getModule();

        $this->assertSame($module, $result);
    }

    /**
     *
     */
    public function testGetAction()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new ControllerAddress($module, $action);

        $result = $address->getAction();

        $this->assertSame($action, $result);
    }

    /**
     *
     */
    public function testControllerAddressCastedAsString()
    {
        $module = 'foo';
        $action = 'bar';

        $address = new ControllerAddress($module, $action);

        $result = (string) $address;

        $this->assertContains($module, $result);
        $this->assertContains($action, $result);
    }
}
