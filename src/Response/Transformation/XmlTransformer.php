<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Header\Header;
use perf\Vc\Header\HeaderCollection;

class XmlTransformer implements TransformerInterface
{
    public const CHARSET = 'charset';

    private const PARAMETERS_DEFAULT = [
        self::CHARSET => 'utf-8',
    ];

    public function transformContent(
        mixed $content,
        array $vars,
        array $parameters
    ): mixed {
        return $content;
    }

    public function transformHeaders(
        HeaderCollection $headers,
        array $vars,
        array $parameters
    ): HeaderCollection {
        $parameters = array_replace(
            self::PARAMETERS_DEFAULT,
            $parameters
        );

        $charset = $parameters[self::CHARSET];

        $headers->replace(new Header('Content-Type', "application/xml; charset={$charset}"));

        return $headers;
    }
}
