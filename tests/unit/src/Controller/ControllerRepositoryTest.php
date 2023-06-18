<?php

namespace perf\Vc\Controller;

use perf\Vc\Exception\ControllerClassNotFoundException;
use perf\Vc\Exception\InvalidControllerException;
use perf\Vc\Routing\Route;
use perf\Vc\Routing\RouteInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

class ControllerRepositoryTest extends TestCase
{
    private MockObject&ControllerClassResolverInterface $controllerClassResolver;

    private Container $container;

    protected function setUp(): void
    {
        $this->controllerClassResolver = $this->createMock(ControllerClassResolverInterface::class);

        $this->container = new Container();
    }

    public function testGetByRouteWithExistingController(): void
    {
        $namespace = 'Foo';

        $controllerRepository = new ControllerRepository(
            $this->controllerClassResolver,
            $namespace,
            $this->container
        );

        $route           = $this->givenRoute();
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

    public function testGetByRouteWithMissingControllerWillThrowException(): void
    {
        $namespace = 'Foo';

        $controllerRepository = new ControllerRepository(
            $this->controllerClassResolver,
            $namespace,
            $this->container
        );

        $route           = $this->givenRoute();
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

    public function testGetByRouteWithInvalidControllerWillThrowException(): void
    {
        $namespace = 'Foo';

        $controllerRepository = new ControllerRepository(
            $this->controllerClassResolver,
            $namespace,
            $this->container
        );

        $route           = $this->givenRoute();
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

    private function givenRoute(): RouteInterface
    {
        return new Route(
            new ControllerAddress('module', 'action'),
            [],
            null
        );
    }
}
