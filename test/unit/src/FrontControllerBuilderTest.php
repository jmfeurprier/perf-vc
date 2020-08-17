<?php

namespace perf\Vc;

use perf\Vc\Routing\RouterInterface;
use PHPUnit\Framework\TestCase;

class FrontControllerBuilderTest extends TestCase
{
    public function testBuild()
    {
        $viewsBasePath = __DIR__;

        $viewFactory = $this->createMock(ViewFactoryInterface::class);

        $router = $this->createMock(RouterInterface::class);

        $frontControllerBuilder = new FrontControllerBuilder();
        $frontControllerBuilder
            ->setViewFactory($viewFactory)
            ->setRouter($router)
        ;

        $result = $frontControllerBuilder->build();

        $this->assertInstanceOf(FrontController::class, $result);
    }
}
