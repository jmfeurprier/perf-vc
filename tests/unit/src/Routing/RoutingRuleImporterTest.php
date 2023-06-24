<?php

namespace perf\Vc\Routing;

use PHPUnit\Framework\TestCase;

/**
 * @psalm-import-type RouteDefinitions from RoutingRuleImporter
 */
class RoutingRuleImporterTest extends TestCase
{
    private PathPatternParser $pathPatternParser;

    /**
     * @psalm-var RouteDefinitions
     */
    private array $routeDefinitions;

    protected function setUp(): void
    {
        $this->pathPatternParser = new PathPatternParser();
        $this->routeDefinitions  = [];
    }

    public function testImportWithoutModule(): void
    {
        $this->givenRouteDefinitions([]);

        $result = $this->whenImport();

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutAction(): void
    {
        $this->givenRouteDefinitions(
            [
                'Foo' => [],
            ]
        );

        $result = $this->whenImport();

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithoutRule(): void
    {
        $this->givenRouteDefinitions(
            [
                'Foo' => [
                    'Bar' => [],
                ],
            ]
        );

        $result = $this->whenImport();

        $this->assertCount(0, $result->getAll());
    }

    public function testImportWithPathRule(): void
    {
        $this->givenRouteDefinitions(
            [
                'Hello' => [
                    'World' => [
                        'foo' => [
                        ],
                    ],
                ],
            ]
        );

        $result = $this->whenImport();

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithParameter(): void
    {
        $this->givenRouteDefinitions(
            [
                'Hello' => [
                    'World' => [
                        'hello - world /{foo}' => [
                            'parameters' => [
                                'foo' => [
                                    'format' => '[^/]+',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $result = $this->whenImport();

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithHttpMethods(): void
    {
        $this->givenRouteDefinitions(
            [
                'Hello' => [
                    'World' => [
                        '' => [
                            'methods' => [
                                'GET',
                                'POST',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $result = $this->whenImport();

        $this->assertCount(1, $result->getAll());
    }

    public function testImportWithMultipleRules(): void
    {
        $this->givenRouteDefinitions(
            [
                'Hello' => [
                    'World' => [
                        'foo' => [
                            'methods' => [
                                'POST',
                            ],
                        ],
                        'bar' => [
                            'methods' => [
                                'GET',
                            ],
                        ],
                    ],
                ],
            ]
        );

        $result = $this->whenImport();

        $this->assertCount(2, $result->getAll());
    }

    /**
     * @psalm-param RouteDefinitions $definitions
     */
    private function givenRouteDefinitions(array $definitions): void
    {
        $this->routeDefinitions = $definitions;
    }

    private function whenImport(): RoutingRuleCollection
    {
        $importer = new RoutingRuleImporter($this->pathPatternParser, $this->routeDefinitions);

        return $importer->import();
    }
}
