<?php

namespace perf\Vc\Response\Transformation;

class HtmlTransformer implements TransformerInterface
{
    public const CHARSET = 'charset';

    private const PARAMETERS_DEFAULT = [
        self::CHARSET => 'utf-8',
    ];

    /**
     * {@inheritDoc}
     */
    public function transformContent($content, array $vars, array $parameters)
    {
        return $content;
    }

    /**
     * {@inheritDoc}
     */
    public function transformHeaders(array $headers, array $vars, array $parameters): array
    {
        $parameters = array_replace(
            self::PARAMETERS_DEFAULT,
            $parameters
        );

        $charset = $parameters[self::CHARSET];

        $headers[] = "Content-Type: text/html; charset={$charset}";

        return $headers;
    }
}
