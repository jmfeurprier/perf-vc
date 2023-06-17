<?php

namespace perf\Vc\Exception;

class RequestChannelKeyNotFoundException extends VcException
{
    public function __construct(
        private readonly string $key
    ) {
        parent::__construct("Request channel key '{$key}' not set.");
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
