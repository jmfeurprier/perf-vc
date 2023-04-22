<?php

namespace perf\Vc\Response;

use perf\Vc\Exception\VcException;
use perf\Vc\Routing\RouteInterface;

interface ResponseBuilderInterface
{
    public function setHttpStatusCode(int $code): self;

    public function addHeader(
        string $header,
        string $value
    );

    public function addRawHeader(string $header): self;

    public function setContent(mixed $content): self;

    public function setVars(array $vars): self;

    public function setVar(
        string $key,
        mixed $value
    ): self;

    public function vars(): KeyValueCollection;

    public function addTransformation(
        string $transformerClass,
        array $parameters = []
    ): self;

    /**
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
