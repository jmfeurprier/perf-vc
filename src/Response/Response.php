<?php

namespace perf\Vc\Response;

use perf\Source\SourceInterface;
use perf\Vc\Header\Header;

class Response implements ResponseInterface
{
    /**
     * @var Header[]
     */
    private array $headers = [];

    /**
     * @param Header[] $headers
     */
    public function __construct(
        array $headers,
        private readonly SourceInterface $source
    ) {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }
    }

    private function addHeader(Header $header): void
    {
        $this->headers[] = $header;
    }

    /**
     * @return Header[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContent(): SourceInterface
    {
        return $this->source;
    }
}
