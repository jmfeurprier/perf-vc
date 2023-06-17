<?php

namespace perf\Vc\Routing;

use perf\Source\SourceInterface;
use perf\Vc\Exception\VcException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class YamlRoutingRuleImporterTest extends TestCase
{
    private PathPatternParser $pathPatternParser;

    private YamlRoutingRuleImporter $importer;

    /**
     * @var SourceInterface|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Source\SourceInterface $source;

    protected function setUp(): void
    {
        $this->pathPatternParser = new PathPatternParser();

        $this->importer = new YamlRoutingRuleImporter($this->pathPatternParser);

        $this->source = $this->createMock(SourceInterface::class);
    }

    public function testImportWithNotYamlSourceWillThrowException()
    {
        $this->givenYaml(<<<YAML
<not-yaml>
YAML
        );

        $this->expectException(VcException::class);

        $this->importer->import($this->source);
    }

    public function testImportWithoutModule()
    {
        $this->givenYaml('');

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutAction()
    {
        $this->givenYaml(<<<YAML
Foo:
    
YAML
        );

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutRule()
    {
        $this->givenYaml(<<<YAML
Foo:
    Bar:
YAML
        );

        $result = $this->importer->import($this->source);

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithPathRule()
    {
        $this->givenYaml(<<<YAML
Hello:
    World:
        -
            path: 'foo'
YAML
        );

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithParameter()
    {
        $this->givenYaml(<<<YAML
Hello:
    World:
        -
            path: 'hello-world/{foo}'
            parameters:
                foo:
                    format: '[^/]+'
YAML
        );

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithHttpMethods()
    {
        $this->givenYaml(<<<YAML
Hello:
    World:
        -
            path: ''
            methods:
                - 'GET'
                - 'POST'
YAML
        );

        $result = $this->importer->import($this->source);

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithMultipleRules()
    {
        $yaml = <<<YAML
Hello:
    World:
        -
            path: 'foo'
            methods:
                - 'POST'
        -
            path: 'bar'
            methods:
                - 'GET'
YAML;

        $this->source->expects($this->once())->method('getContent')->willReturn($yaml);

        $result = $this->importer->import($this->source);

        $this->assertCount(2, $result->getAll());
    }

    private function givenYaml($yaml): void
    {
        $this->source->expects($this->once())->method('getContent')->willReturn($yaml);
    }
}
