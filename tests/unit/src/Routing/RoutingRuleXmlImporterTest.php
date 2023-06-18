<?php

namespace perf\Vc\Routing;

use perf\Source\SourceInterface;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoutingRuleXmlImporterTest extends TestCase
{

    private XmlRoutingRuleImporter $importer;

    private MockObject&SourceInterface $source;

    protected function setUp(): void
    {
        $pathPatternParser = new PathPatternParser();

        $this->source = $this->createMock(SourceInterface::class);

        $this->importer = new XmlRoutingRuleImporter($pathPatternParser);
    }

    public function testImportWithNotXmlSourceWillThrowException(): void
    {
        $xml = '';

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $this->expectException(VcException::class);

        $this->importer->import($this->source);
    }

    public function testImportWithoutModule(): void
    {
        $xml = '<nothing />';

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutAction(): void
    {
        $xml = <<<XML
<root>
    <module id="foo" />
</root>
XML;

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutRule(): void
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar" />
    </module>
</root>
XML;

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithImplicitLiteralPathRule(): void
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithExplicitLiteralPathRule(): void
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithRegexPathRule(): void
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithArgument(): void
    {
        $xml = <<<XML
<root>
    <module id="foo">
        <action id="bar">
            <rule path="foo/{id}" />
        </action>
    </module>
</root>
XML;

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithHttpMethods(): void
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithMultipleRules(): void
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(2, $result->getAll());
    }
}
