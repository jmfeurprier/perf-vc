<?php

namespace perf\Vc\Response\Transformation;

use perf\Vc\Exception\TransformerNotFoundException;

interface TransformerRepositoryInterface
{
    /**
     * @throws TransformerNotFoundException
     */
    public function get(string $class): TransformerInterface;
}
