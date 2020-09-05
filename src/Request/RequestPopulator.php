<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\VcException;

class RequestPopulator implements RequestPopulatorInterface
{
    private array $get;

    private array $post;

    private array $cookies;

    private array $files;

    private array $server;

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function createDefault(): self
    {
        return new self(
            $_GET ?? [],
            $_POST ?? [],
            $_COOKIE ?? [],
            $_FILES ?? [],
            $_SERVER ?? []
        );
    }

    public function __construct(array $get, array $post, array $cookies, array $files, array $server)
    {
        $this->get     = $get;
        $this->post    = $post;
        $this->cookies = $cookies;
        $this->files   = $files;
        $this->server  = $server;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(): RequestInterface
    {
        return new Request(
            $this->getMethod(),
            $this->getTransport(),
            $this->getHost(),
            $this->getPort(),
            $this->getPath(),
            $this->get,
            $this->getAttachment(),
            $this->cookies,
            $this->server
        );
    }

    /**
     * @return string
     *
     * @throws VcException
     */
    private function getMethod(): string
    {
        if (array_key_exists('REQUEST_METHOD', $this->server)) {
            return $this->server['REQUEST_METHOD'];
        }

        throw new VcException('Failed to retrieve HTTP method.');
    }

    private function getTransport(): string
    {
        if (isset($this->server['HTTPS'])) {
            if ($this->server['HTTPS'] !== 'off') {
                return RequestInterface::TRANSPORT_HTTPS;
            }
        }

        return RequestInterface::TRANSPORT_HTTP;
    }

    private function getHost(): string
    {
        // @todo
        return 'localhost';
    }

    private function getPort(): int
    {
        // @todo
        return 80;
    }

    /**
     * @return string
     *
     * @throws VcException
     */
    private function getPath()
    {
        $url = $this->server['REDIRECT_URL'] ?? $this->server['REQUEST_URI'] ?? null;

        if (null === $url) {
            throw new VcException('Failed to retrieve HTTP request path.');
        }

        $path = parse_url($url, PHP_URL_PATH);

        if (false === $path) {
            throw new VcException("Failed to retrieve HTTP request path from URL '{$url}'.");
        }

        return $path;
    }

    private function getAttachment(): array
    {
        $attachment = [];

        switch ($this->getMethod()) {
            case 'POST':
                $attachment = $this->post;
                break;

            case 'DELETE':
            case 'PATCH':
            case 'PUT':
                parse_str(file_get_contents('php://input'), $attachment);
                break;
        }

        foreach ($this->files as $key => $file) {
            $attachment[$key] = $file;
        }

        return $attachment;
    }
}
