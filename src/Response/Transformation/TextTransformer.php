<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;
use perf\Vc\Header\HeaderCollection;
use TypeError;

class TextTransformer implements TransformerInterface
{
    final public const CHARSET = 'charset';

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

        $charset = $this->getCharset($parameters);

        $headers->replace(
            new Header(
                'Content-Type',
                "text/plain; charset={$charset}"
            )
        );

        return $headers;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @throws VcException
     */
    private function getCharset(array $parameters): string
    {
        $charset = $parameters[self::CHARSET];

        if (is_string($charset)) {
            return $charset;
        }

        throw new TypeError('Invalid charset value type for XML transformer.');
    }
}
