<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Response\Transformation\TransformerRepositoryInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;

readonly class ResponseBuilderFactory implements ResponseBuilderFactoryInterface
{
    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private HttpStatusRepositoryInterface $httpStatusRepository,
        private ViewLocatorInterface $templateLocator,
        private ViewRendererInterface $templateRenderer,
        private TransformerRepositoryInterface $transformerRepository,
        private array $vars = []
    ) {
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
