<?php

namespace perf\Vc\Response\Transformation;

interface TransformerInterface
{
    /**
     * @param mixed $content
     * @param array $vars
     * @param array $parameters
     *
     * @return mixed
     */
    public function transformContent($content, array $vars, array $parameters);

    /**
     * @param array $headers
     * @param array $vars
     * @param array $parameters
     *
     * @return array
     */
    public function transformHeaders(array $headers, array $vars, array $parameters): array;
}
