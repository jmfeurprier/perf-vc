<?php

namespace perf\Vc\DependencyInjection;

use perf\Vc\FrontControllerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class VcExtensionTest extends TestCase
{
    private VcExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new VcExtension();
    }

    public function testLoad(): void
    {
        $containerBuilder = new ContainerBuilder();

        $configs = [
            'perf_vc' => [
                'controllers_namespace' => 'Foo',
                'route_definitions'     => [],
                'view_files_base_path'  => '.',
                'twig_extensions'       => [
                    'Foo',
                ],
            ],
        ];

        $this->extension->load($configs, $containerBuilder);

        $this->assertTrue($containerBuilder->has(FrontControllerInterface::class));
    }
}
