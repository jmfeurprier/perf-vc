<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Routing\RouterInterface;

interface RedirectionInterface
{
    public function getHttpStatusCode(): int;

    public function getUrl(RequestInterface $request, RouterInterface $router): string;
}
