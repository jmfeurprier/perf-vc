<?php

namespace perf\Vc\Exception;

class TransformerNotFoundException extends VcException
{
    private string $class;

    public function __construct(string $class)
    {
        parent::__construct("Transformer {$class} not found.");

        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
