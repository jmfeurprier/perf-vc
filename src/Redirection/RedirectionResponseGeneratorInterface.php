<?php

namespace perf\Vc\Redirection;

use perf\Vc\Exception\VcException;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;

interface RedirectionResponseGeneratorInterface
{
    /**
     * @throws VcException
     */
    public function generate(
        RequestInterface $request,
        string $url,
        int $httpStatusCode,
        string $httpVersion = null
    ): ResponseInterface;
}
