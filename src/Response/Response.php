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

    private SourceInterface $source;

    /**
     * @param Header[] $headers
     */
    public function __construct(array $headers, SourceInterface $source)
    {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }

        $this->source = $source;
    }

    private function addHeader(Header $header): void
    {
        $this->headers[] = $header;
    }

    /**
     * @return string[]
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
