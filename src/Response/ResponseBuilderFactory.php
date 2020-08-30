<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Response\Transformation\TransformerRepositoryInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;

class ResponseBuilderFactory implements ResponseBuilderFactoryInterface
{
    private HttpStatusRepositoryInterface $httpStatusRepository;

    private ViewLocatorInterface $templateLocator;

    private ViewRendererInterface $templateRenderer;

    private TransformerRepositoryInterface $transformerRepository;

    private array $vars;

    public function __construct(
        HttpStatusRepositoryInterface $httpStatusRepository,
        ViewLocatorInterface $templateLocator,
        ViewRendererInterface $templateRenderer,
        TransformerRepositoryInterface $transformerRepository,
        array $vars = []
    ) {
        $this->httpStatusRepository  = $httpStatusRepository;
        $this->templateLocator       = $templateLocator;
        $this->templateRenderer      = $templateRenderer;
        $this->transformerRepository = $transformerRepository;
        $this->vars                  = $vars;
    }

    public function make(): ResponseBuilder
    {
        return new ResponseBuilder(
            $this->httpStatusRepository,
            $this->templateLocator,
            $this->templateRenderer,
            $this->transformerRepository,
            $this->vars
        );
    }
}
