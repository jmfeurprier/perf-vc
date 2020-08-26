<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;

interface RedirectorInterface
{
    public function redirect(
        RequestInterface $request,
        string $url,
        int $httpStatusCode,
        string $httpVersion = null
    ): ResponseInterface;
}
