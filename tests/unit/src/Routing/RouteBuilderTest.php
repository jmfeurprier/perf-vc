<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use PHPUnit\Framework\TestCase;

class RouteBuilderTest extends TestCase
{
    private RouteBuilder $routeBuilder;

    private string $module = '';

    private string $action = '';

    private string $pathTemplate = '';

    private array $arguments = [];

    private RouteInterface $result;

    protected function setUp(): void
    {
        $this->routeBuilder = new RouteBuilder();
    }

    public function testBuildReturnsExpectedAddress()
    {
        $this->givenAddress('Module', 'Action');

        $this->whenBuild();

        $this->assertSame('Module', $this->result->getAddress()->getModule());
        $this->assertSame('Action', $this->result->getAddress()->getAction());
    }

    public function testBuildReturnsExpectedPath()
    {
        $this->givenArgument('foo', 'bar');
        $this->givenPathTemplate('baz/{foo}/qux');

        $this->whenBuild();

        $this->assertSame('baz/bar/qux', $this->result->getPath());
    }

    public function testBuildReturnsExpectedArguments()
    {
        $this->givenArgument('foo', 'bar');

        $this->whenBuild();

        $this->assertSame($this->arguments, $this->result->getArguments());
    }

    private function givenPathTemplate(string $pathTemplate): void
    {
        $this->pathTemplate = $pathTemplate;
    }

    private function givenAddress(string $module, string $action): void
    {
        $this->module = $module;
        $this->action = $action;
    }

    private function givenArgument(string $var, string $value): void
    {
        $this->arguments[$var] = $value;
    }

    private function whenBuild()
    {
        $routingRule = new RoutingRule(
            new ControllerAddress(
                $this->module,
                $this->action
            ),
            $this->pathTemplate,
            [],
            '',
            []
        );

        $this->result = $this->routeBuilder->build($routingRule, $this->arguments);
    }
}
