<?php

namespace perf\Vc;

/**
 * HTTP request.
 *
 */
class Request
{

    const METHOD_GET    = 'GET';
    const METHOD_POST   = 'POST';
    const METHOD_PUT    = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Request HTTP method.
     *
     * @var string
     */
    private $method;

    /**
     * Request path.
     *
     * @var string
     */
    private $path;

    /**
     * GET channel.
     *
     * @var RequestChannel
     */
    private $query;

    /**
     * POST channel.
     *
     * @var RequestChannel
     */
    private $post;

    /**
     * Cookies channel.
     *
     * @var RequestChannel
     */
    private $cookies;

    /**
     * Server channel.
     *
     * @var RequestChannel
     */
    private $server;

    /**
     * Static constructor.
     *
     * @return Request
     * @throws \RuntimeException
     */
    public static function createPopulated()
    {
        return RequestPopulator::create()->populate();
    }

    /**
     * Constructor.
     *
     * @param string         $method  HTTP method.
     * @param string         $path    Request path.
     * @param {string:mixed} $query   GET channel content.
     * @param {string:mixed} $post    POST channel content.
     * @param {string:mixed} $cookies Cookies channel content.
     * @param {string:mixed} $server  Server channel content.
     */
    public function __construct($method, $path, array $query, array $post, array $cookies, array $server)
    {
        $this->method  = $method;
        $this->path    = $path;
        $this->query   = new RequestChannel($query);
        $this->post    = new RequestChannel($post);
        $this->cookies = new RequestChannel($cookies);
        $this->server  = new RequestChannel($server);
    }

    /**
     * Tells whether the HTTP request was initiated through a GET method or not.
     *
     * @return bool
     */
    public function isMethodGet()
    {
        return (self::METHOD_GET === $this->method);
    }

    /**
     * Tells whether the HTTP request was initiated through a POST method or not.
     *
     * @return bool
     */
    public function isMethodPost()
    {
        return (self::METHOD_POST === $this->method);
    }

    /**
     * Tells whether the HTTP request was initiated through a PUT method or not.
     *
     * @return bool
     */
    public function isMethodPut()
    {
        return (self::METHOD_PUT === $this->method);
    }

    /**
     * Tells whether the HTTP request was initiated through a DELETE method or not.
     *
     * @return bool
     */
    public function isMethodDelete()
    {
        return (self::METHOD_DELETE === $this->method);
    }

    /**
     * Returns the request HTTP method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns the request path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the GET channel.
     *
     * @return RequestChannel
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Returns the POST channel.
     *
     * @return RequestChannel
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Returns the cookies channel.
     *
     * @return RequestChannel
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Returns the server channel.
     *
     * @return RequestChannel
     */
    public function getServer()
    {
        return $this->server;
    }
}
