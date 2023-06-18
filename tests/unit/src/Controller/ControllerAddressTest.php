<?php

namespace perf\Vc\Controller;

use PHPUnit\Framework\TestCase;

class ControllerAddressTest extends TestCase
{
    public function testGetModule(): void
    {
        $module = 'foo';
        $action = 'bar';

        $address = new ControllerAddress($module, $action);

        $result = $address->getModule();

        $this->assertSame($module, $result);
    }

    public function testGetAction(): void
    {
        $module = 'foo';
        $action = 'bar';

        $address = new ControllerAddress($module, $action);

        $result = $address->getAction();

        $this->assertSame($action, $result);
    }

    public function testControllerAddressCastedAsString(): void
    {
        $module = 'foo';
        $action = 'bar';

        $address = new ControllerAddress($module, $action);

        $result = (string) $address;

        $this->assertStringContainsString($module, $result);
        $this->assertStringContainsString($action, $result);
    }

    public function testEquality(): void
    {
        $addressPrimary   = new ControllerAddress('Foo', 'Bar');
        $addressSecondary = new ControllerAddress('Foo', 'Bar');

        $this->assertTrue($addressPrimary->equals($addressSecondary));
        $this->assertTrue($addressSecondary->equals($addressPrimary));
    }

    /**
     * @return array<array<mixed>>
     */
    public static function dataProviderInequalities(): array
    {
        return [
            ['Foo', 'Bar', 'Foo', 'Baz'],
            ['Foo', 'Bar', 'Baz', 'Bar'],
        ];
    }

    /**
     * @dataProvider dataProviderInequalities
     */
    public function testInequality(
        string $modulePrimary,
        string $actionPrimary,
        string $moduleSecondary,
        string $actionSecondary
    ): void {
        $addressPrimary   = new ControllerAddress($modulePrimary, $actionPrimary);
        $addressSecondary = new ControllerAddress($moduleSecondary, $actionSecondary);

        $this->assertFalse($addressPrimary->equals($addressSecondary));
        $this->assertFalse($addressSecondary->equals($addressPrimary));
    }
}
