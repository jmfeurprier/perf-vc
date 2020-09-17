<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Routing\RouteInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class ControllerRepositoryTest extends TestCase
{
    /**
     * @var ControllerClassResolverInterface|MockObject
     */
    private $controllerClassResolver;

    private Container $container;

    protected function setUp(): void
    {
        $this->controllerClassResolver = $this->createMock(ControllerClassResolverInterface::class);

        $this->container = new Container();
    }

    public function testGetByRouteWithExistingController()
    {
        $namespace = 'Foo';

        $controllerRepository = new ControllerRepository(
            $this->controllerClassResolver,
            $namespace,
            $this->container
        );

        $route           = $this->createMock(RouteInterface::class);
        $controller      = $this->createMock(ControllerInterface::class);
        $controllerClass = 'Whatever';

        $this->container->set($controllerClass, $controller);

        $this->controllerClassResolver
            ->expects($this->atLeastOnce())
            ->method('resolve')
            ->with($route)
            ->willReturn($controllerClass)
        ;

        $result = $controllerRepository->getByRoute($route);

        $this->assertSame($controller, $result);
    }

    public function testGetByRouteWithMissingControllerWillThrowException()
    {
        $namespace = 'Foo';

        $controllerRepository = new ControllerRepository(
            $this->controllerClassResolver,
            $namespace,
            $this->container
        );

        $route           = $this->createMock(RouteInterface::class);
        $controllerClass = 'Whatever';

        $this->controllerClassResolver
            ->expects($this->atLeastOnce())
            ->method('resolve')
            ->with($route)
            ->willReturn($controllerClass)
        ;

        $this->expectException(ControllerClassNotFoundException::class);

        $controllerRepository->getByRoute($route);
    }

    public function testGetByRouteWithInvalidControllerWillThrowException()
    {
        $namespace = 'Foo';

        $controllerRepository = new ControllerRepository(
            $this->controllerClassResolver,
            $namespace,
            $this->container
        );

        $route           = $this->createMock(RouteInterface::class);
        $controller      = new \stdClass();
        $controllerClass = 'Whatever';

        $this->container->set($controllerClass, $controller);

        $this->controllerClassResolver
            ->expects($this->atLeastOnce())
            ->method('resolve')
            ->with($route)
            ->willReturn($controllerClass)
        ;

        $this->expectException(InvalidControllerException::class);

        $controllerRepository->getByRoute($route);
    }
}
