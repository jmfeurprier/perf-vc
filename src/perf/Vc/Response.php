<?php

namespace perf\Vc;

/**
 * Response.
 *
 */
class Response
{

    /**
     * HTTP headers.
     *
     * @var string[]
     */
    private $headers = array();

    /**
     *
     *
     * @var string
     */
    private $content = '';

    /**
     *
     *
     * @var string
     */
    private $sourcePath;

    /**
     * Adds a HTTP header.
     *
     * @param string $header
     * @return Response Fluent return.
     */
    public function addHeader($header)
    {
        $this->headers[] = (string) $header;

        return $this;
    }

    /**
     *
     *
     * @param string $content
     * @return Response Fluent return.
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     *
     *
     * @param string $path
     * @return Response Fluent return.
     */
    public function setSourcePath($path)
    {
        $this->sourcePath = $path;

        return $this;
    }

    /**
     *
     *
     * @return void
     */
    public function send()
    {
        if (null === $this->sourcePath) {
            $this->sendContent();
        } else {
            $this->sendFile();
        }
    }

    /**
     *
     *
     * @return void
     */
    private function sendContent()
    {
        $this->sendHeaders();

        echo $this->content;
    }

    /**
     *
     *
     * @return void
     */
    private function sendFile()
    {
        if (!is_readable($this->sourcePath)) {
            throw new \RuntimeException('Failed to read source path.');
        }

        $this->sendHeaders();

        readfile($this->sourcePath);
    }

    /**
     *
     *
     * @return void
     */
    private function sendHeaders()
    {
        foreach ($this->headers as $header) {
            header($header);
        }
    }
}
