<?php

namespace perf\Vc\Request;

readonly class Request implements RequestInterface
{
    public function __construct(
        private string $method,
        private string $transport,
        private string $host,
        private int $port,
        private string $path,
        private RequestChannel $query,
        private RequestChannel $post,
        private RequestChannel $cookies,
        private RequestChannel $server
    ) {
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

    public function getPort(): int
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
