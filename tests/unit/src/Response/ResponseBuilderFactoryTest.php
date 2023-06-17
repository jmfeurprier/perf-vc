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

    protected function setUp(): void
    {
        $this->httpStatusRepository  = $this->createMock(HttpStatusRepositoryInterface::class);
        $this->templateLocator       = $this->createMock(ViewLocatorInterface::class);
        $this->templateRenderer      = $this->createMock(ViewRendererInterface::class);
        $this->transformerRepository = $this->createMock(TransformerRepositoryInterface::class);
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
