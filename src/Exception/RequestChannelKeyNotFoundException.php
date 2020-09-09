<?php

namespace perf\Vc\Exception;

class RequestChannelKeyNotFoundException extends VcException
{
    private string $key;

    public function __construct(string $key)
    {
        $message = "Request channel key '{$key}' not set.";

        parent::__construct($message);

        $this->key = $key;
    }
}
