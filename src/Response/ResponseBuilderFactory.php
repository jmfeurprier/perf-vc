<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;

class ResponseBuilderFactory implements ResponseBuilderFactoryInterface
{
    private HttpStatusRepositoryInterface $httpStatusRepository;

    private ViewLocatorInterface $templateLocator;

    private ViewRendererInterface $templateRenderer;

    public function __construct(
        HttpStatusRepositoryInterface $httpStatusRepository,
        ViewLocatorInterface $templateLocator,
        ViewRendererInterface $templateRenderer
    ) {
        $this->httpStatusRepository = $httpStatusRepository;
        $this->templateLocator      = $templateLocator;
        $this->templateRenderer     = $templateRenderer;
    }

    public function create(): ResponseBuilder
    {
        return new ResponseBuilder(
            $this->httpStatusRepository,
            $this->templateLocator,
            $this->templateRenderer
        );
    }
}
