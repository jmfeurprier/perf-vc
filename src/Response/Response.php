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
     * @param Header[]        $headers
     * @param SourceInterface $source
     */
    public function __construct(array $headers, SourceInterface $source)
    {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }

        $this->source = $source;
    }

    private function addHeader(Header $header)
    {
        $this->headers[] = $header;
    }

    public function send(): void
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    public function sendHeaders(): void
    {
        foreach ($this->headers as $header) {
            $header->send();
        }
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function sendContent(): void
    {
        $this->source->send();
    }

    public function getContent(): string
    {
        return $this->source->getContent();
    }
}
