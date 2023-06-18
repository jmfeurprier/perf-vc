<?php

namespace perf\Vc\Response;

use perf\Source\SourceInterface;
use perf\Vc\Exception\VcException;
use perf\Vc\Routing\RouteInterface;

interface ResponseBuilderInterface
{
    public function setHttpStatusCode(int $code): self;

    public function addHeader(
        string $header,
        string $value
    ): self;

    public function addRawHeader(string $header): self;

    public function setContent(string|SourceInterface $content): self;

    /**
     * @param array<string, mixed> $vars
     */
    public function setVars(array $vars): self;

    public function setVar(
        string $key,
        mixed $value
    ): self;

    public function vars(): KeyValueCollection;

    /**
     * @param array<string, mixed> $parameters
     */
    public function addTransformation(
        string $transformerClass,
        array $parameters = []
    ): self;

    /**
     * @param array<string, mixed> $vars
     *
     * @throws VcException
     */
    public function renderTemplate(
        RouteInterface $route,
        array $vars = []
    ): void;

    /**
     * @throws VcException
     */
    public function build(RouteInterface $route): ResponseInterface;
}
