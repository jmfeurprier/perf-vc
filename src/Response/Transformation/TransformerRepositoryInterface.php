<?php

namespace perf\Vc\Response\Transformation;

interface TransformerRepositoryInterface
{
    public function get(string $class): TransformerInterface;
}
