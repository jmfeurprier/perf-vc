<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

readonly class PathRedirection implements RedirectionInterface
{
    use PathToUrlTrait;

    private string $path;

    public function __construct(
        string $path,
        private int $httpStatusCode
    ) {
        $this->path = ltrim($path, '/');
    }

    public function getUrl(
        RequestInterface $request,
        RouterInterface $router
    ): string {
        return $this->getUrlFromPath($request, $this->path);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
