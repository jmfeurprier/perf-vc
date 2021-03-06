<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusInterface;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Header\Header;
use perf\Vc\Redirection\RedirectionHeadersGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RedirectionHeadersGeneratorTest extends TestCase
{
    /**
     * @var HttpStatusRepositoryInterface|MockObject
     */
    private $httpStatusRepository;

    private RedirectionHeadersGenerator $redirectionHeadersGenerator;

    protected function setUp(): void
    {
        $this->httpStatusRepository = $this->createMock(HttpStatusRepositoryInterface::class);

        $this->redirectionHeadersGenerator = new RedirectionHeadersGenerator(
            $this->httpStatusRepository
        );
    }

    public function testGenerate()
    {
        $url = 'https://foo.bar/baz';

        $httpStatus = $this->createMock(HttpStatusInterface::class);

        $this->httpStatusRepository
            ->expects($this->atLeastOnce())
            ->method('get')
            ->with(301, '1.1')
            ->willReturn($httpStatus)
        ;

        $result = $this->redirectionHeadersGenerator->generate($url, 301, '1.1');

        $this->assertCount(2, $result);
        $this->assertContainsOnly(Header::class, $result);
    }
}
