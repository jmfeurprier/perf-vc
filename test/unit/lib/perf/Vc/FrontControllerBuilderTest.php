<?php

namespace perf\Vc;

/**
 *
 */
class FrontControllerBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testBuild()
    {
        $viewsBasePath = __DIR__;

        $viewFactory = $this->getMock('\\perf\\Vc\\ViewFactoryInterface');

        $router = $this->getMock('\\perf\\Vc\\Routing\\RouterInterface');

        $frontControllerBuilder = new FrontControllerBuilder();
        $frontControllerBuilder
            ->setViewFactory($viewFactory)
            ->setRouter($router)
        ;

        $result = $frontControllerBuilder->build();

        $this->assertInstanceOf('\\perf\\Vc\\FrontController', $result);
    }
}
