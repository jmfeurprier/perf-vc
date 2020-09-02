<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Exception\VcException;

class TransformerRepository implements TransformerRepositoryInterface
{
    /**
     * @var TransformerInterface[]
     */
    private array $transformers = [];

    public static function createDefault(): self
    {
        return new self(
            [
                new HtmlTransformer(),
                new JsonTransformer(),
                new TextTransformer(),
                new XmlTransformer(),
            ]
        );
    }

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
        $class = get_class($transformer);

        $this->transformers[$class] = $transformer;
    }

    public function get(string $class): TransformerInterface
    {
        if (array_key_exists($class, $this->transformers)) {
            return $this->transformers[$class];
        }

        throw new VcException('Transformer not found.');
    }
}
