<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\HeaderCollection;

interface TransformerInterface
{
    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function transformContent(
        $content,
        array $vars,
        array $parameters
    );

    public function transformHeaders(
        HeaderCollection $headers,
        array $vars,
        array $parameters
    ): HeaderCollection;
}
