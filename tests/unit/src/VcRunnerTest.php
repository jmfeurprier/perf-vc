<?php

namespace perf\Vc;

use perf\Vc\Controller\ControllerInterface;
use perf\Vc\Exception\VcException;
use perf\Vc\Request\Request;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseSenderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

class VcRunnerTest extends TestCase
{
    private VcRunner $runner;

    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->runner = new VcRunner();

        $this->container = new Container();
    }

    public function testRunWithEmptyContainer()
    {
        $this->expectException(VcException::class);

        $this->runner->run($this->container);
    }

    public function testRunWithRequiredContainerParameters()
    {
        $request = new Request('GET', 'http', 'localhost', 80, '/test-path/123', [], [], [], []);

        $controller = $this->createMock(ControllerInterface::class);

        $responseSender = $this->createMock(ResponseSenderInterface::class);
        $responseSender->expects($this->once())->method('send');

        $this->container->setParameter(VcRunner::CONTAINER_PARAMETER_VIEW_FILES_BASE_PATH, '');
        $this->container->setParameter(VcRunner::CONTAINER_PARAMETER_CONTROLLER_NAMESPACE, 'TestNamespace');
        $this->container->setParameter(
            VcRunner::CONTAINER_PARAMETER_ROUTING_RULES_FILE_PATH,
            __DIR__ . '/../fixtures/routes.yml'
        );

        $this->container->set(RequestInterface::class, $request);
        $this->container->set('TestNamespace\\TestModule\\TestActionController', $controller);
        $this->container->set(ResponseSenderInterface::class, $responseSender);

        $this->runner->run($this->container);
    }
}
