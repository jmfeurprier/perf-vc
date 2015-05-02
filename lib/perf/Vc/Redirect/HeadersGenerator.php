<?php

namespace perf\Vc\Redirect;

/**
 * This class allows to generate HTTP headers to redirect visitors to other web sites / pages.
 */
class HeadersGenerator
{

    /**
     * HTTP protocol being used.
     *
     * @var HttpProtocol
     */
    private $protocol;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        // Default protocol.
        $this->setHttpProtocol(new Http11Protocol());
    }

    /**
     * Sets HTTP protocol.
     *
     * @param HttpProtocol $protocol HTTP protocol to be used.
     * @return void
     */
    public function setHttpProtocol(HttpProtocol $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * Generates HTTP headers with specified HTTP status code.
     *
     * @param string $url URL to redirect to.
     * @param int $httpStatusCode HTTP status code to use for the redirect.
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
