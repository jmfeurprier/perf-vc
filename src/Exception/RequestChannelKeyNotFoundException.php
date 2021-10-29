<?php

namespace perf\Vc\Exception;

class RequestChannelKeyNotFoundException extends VcException
{
    private string $key;

    public function __construct(string $key)
    {
        parent::__construct("Request channel key '{$key}' not set.");

        $this->key = $key;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
