<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\Header;

class XmlTransformer implements TransformerInterface
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

        $headers[] = new Header('Content-Type', "application/xml; charset={$charset}");

        return $headers;
    }
}
