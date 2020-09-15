<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

class UrlRedirection implements RedirectionInterface
{
    private string $url;

    private int $httpStatusCode;

    public function __construct(string $url, int $httpStatusCode)
    {
        $this->url            = $url;
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getUrl(RequestInterface $request, RouterInterface $router): string
    {
        return $this->url;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
