<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\HeaderCollection;

interface TransformerInterface
{
    /**
     * @param array<string, mixed> $vars
     * @param array<string, mixed> $parameters
     */
    public function transformContent(
        mixed $content,
        array $vars,
        array $parameters
    ): mixed;

    /**
     * @param array<string, mixed> $vars
     * @param array<string, mixed> $parameters
     */
    public function transformHeaders(
        HeaderCollection $headers,
        array $vars,
        array $parameters
    ): HeaderCollection;
}
