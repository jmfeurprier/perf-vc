<?php

namespace perf\Vc\Response\Transformation;

readonly class Transformation
{
    public function __construct(
        private TransformerInterface $transformer,
        private array $parameters = []
    ) {
    }

    public function getTransformer(): TransformerInterface
    {
        return $this->transformer;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
