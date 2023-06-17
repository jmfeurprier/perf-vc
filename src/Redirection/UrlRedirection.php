<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

readonly class UrlRedirection implements RedirectionInterface
{
    public function __construct(
        private string $url,
        private int $httpStatusCode
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(
        RequestInterface $request,
        RouterInterface $router
    ): string {
        return $this->url;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
