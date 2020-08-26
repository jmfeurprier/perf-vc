<?php

namespace perf\Vc\Redirection;

use perf\HttpStatus\HttpStatusRepository;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Header\Header;

class RedirectionHeadersGenerator implements RedirectionHeadersGeneratorInterface
{
    private HttpStatusRepositoryInterface $httpStatusRepository;

    public static function createDefault(): self
    {
        return new self(
            HttpStatusRepository::createDefault()
        );
    }

    public function __construct(HttpStatusRepositoryInterface $httpStatusRepository)
    {
        $this->httpStatusRepository = $httpStatusRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function generate(string $url, int $httpStatusCode, string $httpVersion): array
    {
        // @todo Validate URL.

        $httpStatus = $this->getHttpStatusRepository()->get($httpStatusCode, $httpVersion);

        return [
            new Header($httpStatus->toHeader()),
            new Header('Location', $url),
        ];
    }

    /**
     * @return HttpStatusRepositoryInterface
     */
    private function getHttpStatusRepository(): HttpStatusRepositoryInterface
    {
        return $this->httpStatusRepository;
    }
}
