<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Exception\VcException;
use perf\Vc\Header\HeaderCollection;

interface TransformerInterface
{
    /**
     * @param array<string, mixed> $vars
     * @param array<string, mixed> $parameters
     *
     * @throws VcException
     */
    public function transformContent(
        mixed $content,
        array $vars,
        array $parameters
    ): mixed;

    /**
     * @param array<string, mixed> $vars
     * @param array<string, mixed> $parameters
     *
     * @throws VcException
     */
    public function transformHeaders(
        HeaderCollection $headers,
        array $vars,
        array $parameters
    ): HeaderCollection;
}
