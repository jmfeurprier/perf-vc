<?php

namespace perf\Vc\Request;

/**
 * HTTP request interface.
 *
 */
interface RequestInterface
{

    const METHOD_GET     = 'GET';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_PATCH   = 'PATCH';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * Tells whether the HTTP request was initiated through a GET method or not.
     *
     * @return bool
     */
    public function isMethodGet();

    /**
     * Tells whether the HTTP request was initiated through a POST method or not.
     *
     * @return bool
     */
    public function isMethodPost();

    /**
     * Tells whether the HTTP request was initiated through a PUT method or not.
     *
     * @return bool
     */
    public function isMethodPut();

    /**
     * Tells whether the HTTP request was initiated through a DELETE method or not.
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
