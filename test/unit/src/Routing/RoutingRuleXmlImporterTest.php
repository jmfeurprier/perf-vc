<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use perf\Source\SourceInterface;

class RoutingRuleXmlImporterTest extends TestCase
{
    protected function setUp()
    {
        $this->importer = new RoutingRuleXmlImporter();
    }

    public function testImportWithNotXmlSourceWillThrowException()
    {
        $xml = '';

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $this->expectException(RuntimeException::class);

        $this->importer->import($source);
    }

    public function testImportWithoutModule()
    {
        $xml = '<nothing />';

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    public function testImportWithoutAction()
    {
        $xml = <<<XML
<root>
    <module id="foo" />
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    public function testImportWithoutRule()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar" />
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(0, $result);
    }

    public function testImportWithImplicitLiteralPathRule()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule path="/foo" />
        </action>
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertContainsOnly(RoutingRule::class, $result);
    }

    public function testImportWithExplicitLiteralPathRule()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule type="literal" path="/foo" />
        </action>
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertContainsOnly(RoutingRule::class, $result);
    }

    public function testImportWithRegexPathRule()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule type="regex" pattern="^foo$" />
        </action>
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertContainsOnly(RoutingRule::class, $result);
    }

    public function testImportWithRegexPathRuleAndParameters()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule type="regex" pattern="^foo/([a-z])/(\d+)$">
                <parameter>bar</parameter>
                <parameter>baz</parameter>
            </rule>
        </action>
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertContainsOnly(RoutingRule::class, $result);
    }

    public function testImportWithHttpMethods()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule path="/foo" method="GET POST" />
        </action>
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertContainsOnly(RoutingRule::class, $result);
    }

    public function testImportWithMultipleRules()
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule path="/foo" />
            <rule path="/bar" />
        </action>
    </module>
</root>
XML;

        $source = $this->createMock(SourceInterface::class);
        $source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($source);

        $this->assertInternalType('array', $result);
        $this->assertCount(2, $result);
        $this->assertContainsOnly(RoutingRule::class, $result);
    }
}
