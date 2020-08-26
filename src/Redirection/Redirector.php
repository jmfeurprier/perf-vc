<?php

namespace perf\Vc\Redirection;

use perf\Source\NullSource;
use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;
use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\Response;
use perf\Vc\Response\ResponseInterface;

class Redirector implements RedirectorInterface
{
    private RedirectionHeadersGeneratorInterface $redirectionHeadersGenerator;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    private string $url;

    private int $httpStatusCode;

    private ?string $httpVersion;

    public static function createDefault(): self
    {
        return new self(
            RedirectionHeadersGenerator::createDefault()
        );
    }

    public function __construct(
        RedirectionHeadersGeneratorInterface $redirectionHeadersGenerator
    ) {
        $this->redirectionHeadersGenerator = $redirectionHeadersGenerator;
    }

    public function redirect(
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
     * @throws VcException
     */
    public function getHeaders(): array
    {
        return $this->redirectionHeadersGenerator
            ->generate(
                $this->url,
                $this->httpStatusCode,
                $this->getHttpVersion()
            );
    }

    private function getHttpVersion(): string
    {
        if (null !== $this->httpVersion) {
            return $this->httpVersion;
        }

        $httpVersion = substr(
            $this->request->getServer()->get('SERVER_PROTOCOL'),
            5
        );

        return $httpVersion;
    }
}
