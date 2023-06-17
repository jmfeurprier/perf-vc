<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

class UrlRedirection implements RedirectionInterface
{
    public function __construct(private readonly string $url, private readonly int $httpStatusCode)
    {
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
