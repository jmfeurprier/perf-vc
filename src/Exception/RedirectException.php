<?php

namespace perf\Vc\Exception;

use Exception;

class RedirectException extends Exception
{
    private string $url;

    private int $httpStatusCode;

    public function __construct(string $url, int $httpStatusCode)
    {
        parent::__construct();

        $this->url            = $url;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
