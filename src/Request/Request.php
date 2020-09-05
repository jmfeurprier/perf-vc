<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\VcException;

class Request implements RequestInterface
{
    private string $method;

    private string $transport;

    private string $host;

    private int $port;

    private string $path;

    private RequestChannel $query;

    private RequestChannel $post;

    private RequestChannel $cookies;

    private RequestChannel $server;

    /**
     * @return RequestInterface
     *
     * @throws VcException
     */
    public static function createDefault(): RequestInterface
    {
        return RequestPopulator::createDefault()->populate();
    }

    /**
     * @param string $method HTTP method.
     * @param string $path   Request path.
     * @param string $transport
     * @param string $host
     * @param int    $port
     * @param {string:mixed} $query     GET channel content.
     * @param {string:mixed} $post      POST channel content.
     * @param {string:mixed} $cookies   Cookies channel content.
     * @param {string:mixed} $server    Server channel content.
     */
    public function __construct(
        string $method,
        string $transport,
        string $host,
        int $port,
        string $path,
        array $query,
        array $post,
        array $cookies,
        array $server
    ) {
        $this->method    = $method;
        $this->transport = $transport;
        $this->host      = $host;
        $this->port      = $port;
        $this->path      = $path;
        $this->query     = new RequestChannel($query);
        $this->post      = new RequestChannel($post);
        $this->cookies   = new RequestChannel($cookies);
        $this->server    = new RequestChannel($server);
    }

    public function isMethodGet(): bool
    {
        return (self::METHOD_GET === $this->method);
    }

    public function isMethodPost(): bool
    {
        return (self::METHOD_POST === $this->method);
    }

    public function isMethodPut(): bool
    {
        return (self::METHOD_PUT === $this->method);
    }

    public function isMethodDelete(): bool
    {
        return (self::METHOD_DELETE === $this->method);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getTransport(): string
    {
        return $this->transport;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): RequestChannel
    {
        return $this->query;
    }

    public function getPost(): RequestChannel
    {
        return $this->post;
    }

    public function getCookies(): RequestChannel
    {
        return $this->cookies;
    }

    public function getServer(): RequestChannel
    {
        return $this->server;
    }
}
