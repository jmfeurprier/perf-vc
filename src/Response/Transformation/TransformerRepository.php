<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Exception\TransformerNotFoundException;

class TransformerRepository implements TransformerRepositoryInterface
{
    /**
     * @var array<string, TransformerInterface>
     */
    private array $transformers = [];

    /**
     * @param TransformerInterface[] $transformers
     */
    public function __construct(array $transformers)
    {
        foreach ($transformers as $transformer) {
            $this->addTransformer($transformer);
        }
    }

    private function addTransformer(TransformerInterface $transformer): void
    {
        $class = $transformer::class;

        $this->transformers[$class] = $transformer;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $class): TransformerInterface
    {
        if (array_key_exists($class, $this->transformers)) {
            return $this->transformers[$class];
        }

        throw new TransformerNotFoundException($class);
    }
}
