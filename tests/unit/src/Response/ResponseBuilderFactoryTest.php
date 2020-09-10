<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Response\Transformation\TransformerRepositoryInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ResponseBuilderFactoryTest extends TestCase
{
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

    /**
     * @var TransformerRepositoryInterface|MockObject
     */
    private $transformerRepository;

    protected function setUp(): void
    {
        $this->httpStatusRepository  = $this->createMock(HttpStatusRepositoryInterface::class);
        $this->templateLocator       = $this->createMock(ViewLocatorInterface::class);
        $this->templateRenderer      = $this->createMock(ViewRendererInterface::class);
        $this->transformerRepository = $this->createMock(TransformerRepositoryInterface::class);
    }

    public function testCreateDefault()
    {
        $viewsBasePath = '';

        $result = ResponseBuilderFactory::createDefault($viewsBasePath);

        $this->assertInstanceOf(ResponseBuilderFactory::class, $result);
    }

    public function testMake()
    {
        $factory = new ResponseBuilderFactory(
            $this->httpStatusRepository,
            $this->templateLocator,
            $this->templateRenderer,
            $this->transformerRepository
        );

        $result = $factory->make();

        $this->assertInstanceOf(ResponseBuilder::class, $result);
    }
}
