<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

class PathRedirection implements RedirectionInterface
{
    use PathToUrlTrait;

    private string $path;

    private int $httpStatusCode;

    public function __construct(string $path, int $httpStatusCode)
    {
        $this->path           = ltrim($path, '/');
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getUrl(RequestInterface $request, RouterInterface $router): string
    {
        return $this->getUrlFromPath($request, $this->path);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
