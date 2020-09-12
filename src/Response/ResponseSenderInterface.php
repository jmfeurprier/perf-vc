<?php

namespace perf\Vc\Response;

use perf\Vc\Exception\VcException;

interface ResponseSenderInterface
{
    /**
     * @param ResponseInterface $response
     *
     * @return void
     *
     * @throws VcException
     */
    public function send(ResponseInterface $response): void;
}
