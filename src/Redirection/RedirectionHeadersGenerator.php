<?php

namespace perf\Vc\Redirection;

/**
 * This class allows to generate HTTP headers to redirect visitors to other web sites / pages.
 */
class RedirectionHeadersGenerator
{
    /**
     * HTTP protocol being used.
     *
     * @var HttpProtocol
     */
    private $protocol;

    /**
     * Static constructor.
     *
     * @return RedirectionHeadersGenerator
     */
    public static function createDefault()
    {
        $protocol = new Http11Protocol();

        return new self($protocol);
    }

    public function __construct(HttpProtocol $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Generates HTTP headers with specified HTTP status code.
     *
     * @param string $url URL to redirect to.
     * @param int $httpStatusCode HTTP status code to use for the redirect.
     *
     * @return string[]
     */
    public function generate($url, $httpStatusCode)
    {
        // @todo Validate URL.

        $httpStatus = $this->protocol->getStatus($httpStatusCode);

        $headers = array(
            $httpStatus->toHeader(),
            "Location: {$url}",
        );

        return $headers;
    }
}
