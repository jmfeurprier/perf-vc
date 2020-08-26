<?php

namespace perf\Vc\Redirection;

use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;

interface RedirectionHeadersGeneratorInterface
{
    /**
     * Generates HTTP headers with specified HTTP status code.
     *
     * @param string $url            URL to redirect to.
     * @param int    $httpStatusCode HTTP status code to use for the redirect.
     * @param string $httpVersion
     *
     * @return Header[]
     *
     * @throws VcException
     */
    public function generate(string $url, int $httpStatusCode, string $httpVersion): array;
}
