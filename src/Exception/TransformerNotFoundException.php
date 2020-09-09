<?php

namespace perf\Vc\Exception;

class TransformerNotFoundException extends VcException
{
    private string $class;

    public function __construct(string $class)
    {
        $message = "Transformer {$class} not found.";

        parent::__construct($message);

        $this->class = $class;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
