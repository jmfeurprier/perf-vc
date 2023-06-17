<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusInterface;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Response\Transformation\TransformerRepositoryInterface;
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
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Vc\Routing\RouteInterface $route;

    /**
     * @var HttpStatusRepositoryInterface|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\HttpStatus\HttpStatusRepositoryInterface $httpStatusRepository;

    /**
     * @var ViewLocatorInterface|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Vc\View\ViewLocatorInterface $templateLocator;

    /**
     * @var ViewRendererInterface|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Vc\View\ViewRendererInterface $templateRenderer;

    /**
     * @var TransformerRepositoryInterface|MockObject
     */
    private \PHPUnit\Framework\MockObject\MockObject&\perf\Vc\Response\Transformation\TransformerRepositoryInterface $transformerRepository;

    private ResponseBuilder $responseBuilder;

    protected function setUp(): void
    {
        $this->route = $this->createMock(RouteInterface::class);

        $this->httpStatusRepository = $this->createMock(HttpStatusRepositoryInterface::class);

        $this->templateLocator = $this->createMock(ViewLocatorInterface::class);

        $this->templateRenderer = $this->createMock(ViewRendererInterface::class);

        $this->transformerRepository = $this->createMock(TransformerRepositoryInterface::class);

        $this->responseBuilder = new ResponseBuilder(
            $this->httpStatusRepository,
            $this->templateLocator,
            $this->templateRenderer,
            $this->transformerRepository
        );
    }

    public function testBuildWithContent()
    {
        $content = 'foo';

        $this->responseBuilder->setContent($content);

        $result = $this->responseBuilder->build($this->route);

        $this->assertSame($content, $result->getContent()->getContent());
        $this->assertCount(0, $result->getHeaders());
    }

    public function testBuildWithHttpStatus()
    {
        $header = 'foo';

        $httpStatus = $this->createMock(HttpStatusInterface::class);
        $httpStatus->expects($this->once())->method('toHeader')->willReturn($header);

        $this->httpStatusRepository->expects($this->once())->method('get')->with(200)->willReturn($httpStatus);

        $this->responseBuilder->setHttpStatusCode(200);

        $result = $this->responseBuilder->build($this->route);

        $headers = $result->getHeaders();

        $this->assertCount(1, $headers);
    }

    public function testBuildWithHeader()
    {
        $this->responseBuilder->addHeader('Foo', 'bar');

        $result = $this->responseBuilder->build($this->route);

        $headers = $result->getHeaders();

        $this->assertCount(1, $headers);
    }
}
