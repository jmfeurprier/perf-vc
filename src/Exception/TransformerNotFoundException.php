<?php

namespace perf\Vc\Exception;

class TransformerNotFoundException extends VcException
{
    public function __construct(
        private readonly string $class
    ) {
        parent::__construct("Transformer {$class} not found.");
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
