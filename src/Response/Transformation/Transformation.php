<?php

namespace perf\Vc\Response\Transformation;

readonly class Transformation
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private TransformerInterface $transformer,
        private array $parameters = []
    ) {
    }

    public function getTransformer(): TransformerInterface
    {
        return $this->transformer;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
