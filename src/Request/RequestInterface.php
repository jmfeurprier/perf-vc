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

    public const TRANSPORT_HTTP  = 'HTTP';
    public const TRANSPORT_HTTPS = 'HTTPS';

    public function isMethodGet(): bool;

    public function isMethodPost(): bool;

    public function isMethodPut(): bool;

    public function isMethodDelete(): bool;

    public function getMethod(): string;

    public function getTransport(): string;

    public function getHost(): string;

    public function getPort(): string;

    public function getPath(): string;

    public function getQuery(): RequestChannel;

    public function getPost(): RequestChannel;

    public function getCookies(): RequestChannel;

    public function getServer(): RequestChannel;
}
