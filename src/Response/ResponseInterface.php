<?php

namespace perf\Vc\Response;

use perf\Source\SourceInterface;
use perf\Vc\Header\Header;

interface ResponseInterface
{
    /**
     * @return Header[]
     */
    public function getHeaders(): array;

    public function getContent(): SourceInterface;
}
