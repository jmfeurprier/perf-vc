<?php

namespace perf\Vc\Response\Transformation;

class Transformation
{
    private TransformerInterface $transformer;

    private array $parameters;

    public function __construct(TransformerInterface $transformer, array $parameters)
    {
        $this->transformer = $transformer;
        $this->parameters  = $parameters;
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
