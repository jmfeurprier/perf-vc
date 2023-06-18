<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\VcException;

readonly class RequestPopulator implements RequestPopulatorInterface
{
    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function createDefault(): self
    {
        return new self(
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    /**
     * @param array<string, mixed> $get
     * @param array<string, mixed> $post
     * @param array<string, mixed> $cookies
     * @param array<string, mixed> $files
     * @param array<string, mixed> $server
     */
    public function __construct(
        private array $get,
        private array $post,
        private array $cookies,
        private array $files,
        private array $server
    ) {
    }

    public function populate(): RequestInterface
    {
        return new Request(
            $this->getMethod(),
            $this->getTransport(),
            $this->getHost(),
            $this->getPort(),
            $this->getPath(),
            new RequestChannel($this->get),
            new RequestChannel($this->getAttachment()),
            new RequestChannel($this->cookies),
            new RequestChannel($this->server)
        );
    }

    /**
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
        if (array_key_exists('SERVER_NAME', $this->server)) {
            return $this->server['SERVER_NAME'];
        }

        throw new VcException('Failed to retrieve HTTP host.');
    }

    private function getPort(): int
    {
        if (array_key_exists('SERVER_PORT', $this->server)) {
            return $this->server['SERVER_PORT'];
        }

        throw new VcException('Failed to retrieve HTTP port.');
    }

    /**
     * @throws VcException
     */
    private function getPath(): string
    {
        $url = $this->server['REDIRECT_URL'] ?? $this->server['REQUEST_URI'] ?? null;

        if (null === $url) {
            throw new VcException('Failed to retrieve HTTP request path.');
        }

        $path = parse_url((string) $url, PHP_URL_PATH);

        if (!is_string($path)) {
            throw new VcException("Failed to retrieve HTTP request path from URL '{$url}'.");
        }

        return $path;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws VcException
     */
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
                $string = file_get_contents('php://input');
                if (!is_string($string)) {
                    throw new VcException('Failed to read PHP input for request attachment.');
                }
                parse_str($string, $attachment); // @todo Check $attachment keys type.
                break;
        }

        foreach ($this->files as $key => $file) {
            $attachment[$key] = $file;
        }

        return $attachment;
    }
}
