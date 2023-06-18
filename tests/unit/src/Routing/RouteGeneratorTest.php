<?php

namespace perf\Vc\Routing;

use perf\Vc\Controller\ControllerAddress;
use PHPUnit\Framework\TestCase;

class RouteGeneratorTest extends TestCase
{
    private RouteGenerator $routeGenerator;

    private string $module = '';

    private string $action = '';

    private string $pathTemplate = '';

    /**
     * @var array<string, mixed>
     */
    private array $arguments = [];

    private RouteInterface $result;

    protected function setUp(): void
    {
        $this->routeGenerator = new RouteGenerator();
    }

    public function testBuildReturnsExpectedAddress(): void
    {
        $this->givenAddress('Module', 'Action');

        $this->whenGenerate();

        $this->assertSame('Module', $this->result->getAddress()->getModule());
        $this->assertSame('Action', $this->result->getAddress()->getAction());
    }

    public function testBuildReturnsExpectedPath(): void
    {
        $this->givenArgument('foo', 'bar');
        $this->givenPathTemplate('baz/{foo}/qux');

        $this->whenGenerate();

        $this->assertSame('baz/bar/qux', $this->result->getPath());
    }

    public function testBuildReturnsExpectedArguments(): void
    {
        $this->givenArgument('foo', 'bar');

        $this->whenGenerate();

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

    private function givenArgument(string $var, mixed $value): void
    {
        $this->arguments[$var] = $value;
    }

    private function whenGenerate(): void
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

        $this->result = $this->routeGenerator->generate($routingRule, $this->arguments);
    }
}
