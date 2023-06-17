<?php

namespace perf\Vc\Routing;

use perf\Source\SourceInterface;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RoutingRuleXmlImporterTest extends TestCase
{
    /**
     * @var PathPatternParser
     */
    private PathPatternParser $pathPatternParser;

    /**
     * @var XmlRoutingRuleImporter
     */
    private XmlRoutingRuleImporter $importer;

    /**
     * @var SourceInterface|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Source\SourceInterface $source;

    protected function setUp(): void
    {
        $this->pathPatternParser = new PathPatternParser();

        $this->source = $this->createMock(SourceInterface::class);

        $this->importer = new XmlRoutingRuleImporter($this->pathPatternParser);
    }

    public function testImportWithNotXmlSourceWillThrowException()
    {
        $xml = '';

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $this->expectException(VcException::class);

        $this->importer->import($this->source);
    }

    public function testImportWithoutModule()
    {
        $xml = '<nothing />';

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutAction()
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

    /**
     *
     */
    public function testImportWithoutRule()
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

    /**
     *
     */
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    /**
     *
     */
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    /**
     *
     */
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    /**
     *
     */
    public function testImportWithArgument()
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

    /**
     *
     */
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    /**
     *
     */
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

        $this->source->expects($this->once())->method('getContent')->willReturn($xml);

        $result = $this->importer->import($this->source);

        $this->assertCount(2, $result->getAll());
    }
}
