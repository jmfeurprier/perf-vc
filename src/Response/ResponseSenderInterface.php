<?php

namespace perf\Vc\Response;

use perf\Vc\Exception\VcException;

interface ResponseSenderInterface
{
    /**
     * @throws VcException
     */
    public function send(ResponseInterface $response): void;
}
