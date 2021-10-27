<?php

namespace perf\Vc\Redirection;

use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;

interface RedirectionHeadersGeneratorInterface
{
    /**
     * Generates HTTP headers with specified HTTP status code.
     *
     * @return Header[]
     *
     * @throws VcException
     */
    public function generate(
        string $url,
        int $httpStatusCode,
        string $httpVersion
    ): array;
}
