<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\HeaderCollection;

interface TransformerInterface
{
    public function transformContent(
        mixed $content,
        array $vars,
        array $parameters
    ): mixed;

    public function transformHeaders(
        HeaderCollection $headers,
        array $vars,
        array $parameters
    ): HeaderCollection;
}
