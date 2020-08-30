<?php

namespace perf\Vc\Response\Transformation;

class TransformerRepository implements TransformerRepositoryInterface
{
    /**
     * @var TransformerInterface[]
     */
    private array $transformers;

    public static function createDefault(): self
    {
        return new self(
            [
                new HtmlTransformer(),
                new JsonTransformer(),
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
        return $this->transformers[$class];
    }
}
