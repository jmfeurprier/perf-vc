<?php

namespace perf\Vc\Redirection;

use perf\Source\NullSource;
use perf\Vc\Exception\InvalidRequestChannelValueTypeException;
use perf\Vc\Exception\RequestChannelKeyNotFoundException;
use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\Response;
use perf\Vc\Response\ResponseInterface;

class RedirectionResponseGenerator implements RedirectionResponseGeneratorInterface
{
    private RequestInterface $request;

    private string $url;

    private int $httpStatusCode;

    private ?string $httpVersion = null;

    public static function createDefault(): self
    {
        return new self(
            RedirectionHeadersGenerator::createDefault()
        );
    }

    public function __construct(
        private readonly RedirectionHeadersGeneratorInterface $redirectionHeadersGenerator
    ) {
    }

    public function generate(
        RequestInterface $request,
        string $url,
        int $httpStatusCode,
        string $httpVersion = null
    ): ResponseInterface {
        $this->init($request, $url, $httpStatusCode, $httpVersion);

        return new Response(
            $this->getHeaders(),
            NullSource::create()
        );
    }

    private function init(
        RequestInterface $request,
        string $url,
        int $httpStatusCode,
        ?string $httpVersion
    ): void {
        $this->request        = $request;
        $this->url            = $url;
        $this->httpStatusCode = $httpStatusCode;
        $this->httpVersion    = $httpVersion;
    }

    /**
     * @return Header[]
     *
     * @throws RequestChannelKeyNotFoundException
     * @throws VcException
     */
    private function getHeaders(): array
    {
        return $this->redirectionHeadersGenerator->generate(
            $this->url,
            $this->httpStatusCode,
            $this->getHttpVersion()
        );
    }

    /**
     * @throws RequestChannelKeyNotFoundException
     * @throws InvalidRequestChannelValueTypeException
     */
    private function getHttpVersion(): string
    {
        static $key = 'SERVER_PROTOCOL';

        if (null !== $this->httpVersion) {
            return $this->httpVersion;
        }

        $protocol = $this->request->getServer()->get($key);

        if (is_string($protocol)) {
            return substr($protocol, 5);
        }

        throw new InvalidRequestChannelValueTypeException('SERVER_PROTOCOL');
    }
}
