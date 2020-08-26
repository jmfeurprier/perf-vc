<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
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

    protected function setUp(): void
    {
        $this->httpStatusRepository = $this->createMock(HttpStatusRepositoryInterface::class);
        $this->templateLocator      = $this->createMock(ViewLocatorInterface::class);
        $this->templateRenderer     = $this->createMock(ViewRendererInterface::class);
    }

    public function testCreate()
    {
        $factory = new ResponseBuilderFactory(
            $this->httpStatusRepository,
            $this->templateLocator,
            $this->templateRenderer
        );

        $result = $factory->create();

        $this->assertInstanceOf(ResponseBuilder::class, $result);
    }
}
