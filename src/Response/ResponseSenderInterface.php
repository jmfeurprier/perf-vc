<?php

namespace perf\Vc\Response;

interface ResponseSenderInterface
{
    public function send(ResponseInterface $response): void;

    public function sendContent(): void;
}
