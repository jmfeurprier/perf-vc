<?php

namespace perf\Vc\Response;

use perf\Vc\Exception\VcException;
use perf\Vc\Routing\RouteInterface;

interface ResponseBuilderInterface
{
    public function setHttpStatusCode(int $code): self;

    public function addHeader(string $header, string $value);

    public function addRawHeader(string $header): self;

    /**
     * @param mixed $content
     *
     * @return ResponseBuilder
     */
    public function setContent($content): self;

    public function setVars(array $vars): self;

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return ResponseBuilder
     */
    public function setVar(string $key, $value): self;

    public function vars(): KeyValueCollection;

    public function addTransformation(string $transformerClass, array $parameters = []): self;

    /**
     * @param RouteInterface $route
     * @param array          $vars
     *
     * @return void
     *
     * @throws VcException
     */
    public function renderTemplate(RouteInterface $route, array $vars = []): void;

    /**
     * @param RouteInterface $route
     *
     * @return ResponseInterface
     *
     * @throws VcException
     */
    public function build(RouteInterface $route): ResponseInterface;
}
