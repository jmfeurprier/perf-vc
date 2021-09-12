<?php

namespace perf\Vc\Request;

interface RequestInterface
{
    public const METHOD_GET     = 'GET';
    public const METHOD_HEAD    = 'HEAD';
    public const METHOD_PATCH   = 'PATCH';
    public const METHOD_POST    = 'POST';
    public const METHOD_PUT     = 'PUT';
    public const METHOD_DELETE  = 'DELETE';
    public const METHOD_OPTIONS = 'OPTIONS';

    /**
     * Tells whether the HTTP request was initiated through a GET method.
     *
     * @return bool
     */
    public function isMethodGet();

    /**
     * Tells whether the HTTP request was initiated through a POST method.
     *
     * @return bool
     */
    public function isMethodPost();

    /**
     * Tells whether the HTTP request was initiated through a PUT method.
     *
     * @return bool
     */
    public function isMethodPut();

    /**
     * Tells whether the HTTP request was initiated through a DELETE method.
     *
     * @return bool
     */
    public function isMethodDelete();

    /**
     * Returns the request HTTP method.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Returns the request path.
     *
     * @return string
     */
    public function getPath();

    /**
     * Returns the GET channel.
     *
     * @return RequestChannel
     */
    public function getQuery();

    /**
     * Returns the POST channel.
     *
     * @return RequestChannel
     */
    public function getPost();

    /**
     * Returns the cookies channel.
     *
     * @return RequestChannel
     */
    public function getCookies();

    /**
     * Returns the server channel.
     *
     * @return RequestChannel
     */
    public function getServer();
}
