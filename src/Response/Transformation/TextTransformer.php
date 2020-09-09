<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\Header;
use perf\Vc\Header\HeaderCollection;

class TextTransformer implements TransformerInterface
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

    public function transformHeaders(HeaderCollection $headers, array $vars, array $parameters): HeaderCollection
    {
        $parameters = array_replace(
            self::PARAMETERS_DEFAULT,
            $parameters
        );

        $charset = $parameters[self::CHARSET];

        $headers->replace(new Header('Content-Type', "text/plain; charset={$charset}"));

        return $headers;
    }
}
