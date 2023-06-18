<?php

namespace perf\Vc\Exception;

class InvalidRequestChannelValueTypeException extends VcException
{
    public function __construct(
        private readonly string $key
    ) {
        parent::__construct("Invalid request channel value type for key '{$key}'.");
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
