<?php

namespace perf\Vc\Redirection;

use perf\HttpStatus\Exception\HttpProtocolNotFoundException;
use perf\HttpStatus\Exception\HttpStatusNotFoundException;
use perf\HttpStatus\HttpStatusInterface;
use perf\HttpStatus\HttpStatusRepository;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;

readonly class RedirectionHeadersGenerator implements RedirectionHeadersGeneratorInterface
{
    public static function createDefault(): self
    {
        return new self(
            HttpStatusRepository::createDefault()
        );
    }

    public function __construct(
        private HttpStatusRepositoryInterface $httpStatusRepository
    ) {
    }

    public function generate(
        string $url,
        int $httpStatusCode,
        string $httpVersion
    ): array {
        // @todo Validate URL.

        $httpStatus = $this->getHttpStatus($httpStatusCode, $httpVersion);

        return [
            new Header($httpStatus->toHeader()),
            new Header('Location', $url),
        ];
    }

    /**
     * @throws VcException
     */
    private function getHttpStatus(
        int $httpStatusCode,
        string $httpVersion
    ): HttpStatusInterface {
        try {
            return $this->httpStatusRepository->get($httpStatusCode, $httpVersion);
        } catch (HttpProtocolNotFoundException $e) {
            throw new VcException('HTTP protocol not found.', 0, $e);
        } catch (HttpStatusNotFoundException $e) {
            throw new VcException('HTTP status not found.', 0, $e);
        }
    }
}
