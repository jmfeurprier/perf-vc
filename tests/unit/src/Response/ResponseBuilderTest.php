<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Routing\RouteInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ResponseBuilderTest extends TestCase
{
    /**
     * @var RouteInterface|MockObject
     */
    private $route;

    /**
     * @var HttpStatusRepositoryInterface|MockObject
     */
    private $httpStatusRepository;

    /**
     * @var ViewLocatorInterface|MockObject
     */
    private $templateLocator;

    /**
     * @var ViewRendererInterface|MockObject
     */
    private $templateRenderer;

    private ResponseBuilder $responseBuilder;

    protected function setUp(): void
    {
        $this->route = $this->createMock(RouteInterface::class);

        $this->httpStatusRepository = $this->createMock(HttpStatusRepositoryInterface::class);

        $this->templateLocator = $this->createMock(ViewLocatorInterface::class);

        $this->templateRenderer = $this->createMock(ViewRendererInterface::class);

        $this->responseBuilder = new ResponseBuilder(
            $this->httpStatusRepository,
            $this->templateLocator,
            $this->templateRenderer
        );
    }

    public function testBuildWithContent()
    {
        $content = 'foo';

        $this->responseBuilder->setContent($content);

        $result = $this->responseBuilder->build($this->route);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertSame($content, $result->getContent());
        $this->assertCount(0, $result->getHeaders());
    }
}
