<?php

namespace perf\Vc\Response;

use perf\Vc\Header\Header;

interface ResponseInterface
{
    public function send(): void;

    public function sendHeaders(): void;

    public function sendContent(): void;

    /**
     * @return Header[]
     */
    public function getHeaders(): array;

    public function getContent(): string;
}
