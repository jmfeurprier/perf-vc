<?php

namespace perf\Vc\Response;

use perf\Source\Source;

/**
 * Response.
 *
 */
class Response implements ResponseInterface
{

    /**
     * HTTP headers.
     *
     * @var {string:mixed}
     */
    private $headers = array();

    /**
     * Content source.
     *
     * @var Source
     */
    private $source;

    /**
     * Constructor.
     *
     * @param {string:mixed} $headers
     * @param Source         $source
     */
    public function __construct(array $headers, Source $source)
    {
        $this->headers = $headers;
        $this->source  = $source;
    }

    /**
     *
     *
     * @return void
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     *
     *
     * @return void
     */
    public function sendHeaders()
    {
        foreach ($this->getHeaders() as $header) {
            header($header);
        }
    }

    /**
     *
     *
     * @return string[]
     */
    public function getHeaders()
    {
        $headers = array();

        foreach ($this->headers as $header => $value) {
            if (null !== $value) {
                $header .= ": {$value}";
            }

            $headers[] = $header;
        }

        return $headers;
    }

    /**
     *
     *
     * @return void
     */
    public function sendContent()
    {
        $this->source->send();
    }

    /**
     *
     *
     * @return string
     */
    public function getContent()
    {
        return $this->source->getContent();
    }
}
