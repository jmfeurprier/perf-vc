<?php

namespace perf\Vc\Response\Transformation;

class JsonTransformer implements TransformerInterface
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
        return json_encode($content);
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

        $headers[] = "Content-Type: application/json; charset={$charset}";

        return $headers;
    }
}
