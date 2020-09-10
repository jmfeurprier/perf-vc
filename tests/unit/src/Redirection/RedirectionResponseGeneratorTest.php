<?php

namespace perf\Vc\Response;

use perf\Vc\Redirection\RedirectionHeadersGeneratorInterface;
use perf\Vc\Redirection\RedirectionResponseGenerator;
use perf\Vc\Request\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RedirectionResponseGeneratorTest extends TestCase
{
    /**
     * @var RedirectionHeadersGeneratorInterface|MockObject
     */
    private $redirectionHeadersGenerator;

    private RedirectionResponseGenerator $redirectionResponseGenerator;

    /**
     * @var RequestInterface|MockObject
     */
    private $request;

    protected function setUp(): void
    {
        $this->redirectionHeadersGenerator = $this->createMock(RedirectionHeadersGeneratorInterface::class);

        $this->redirectionResponseGenerator = new RedirectionResponseGenerator(
            $this->redirectionHeadersGenerator
        );

        $this->request = $this->createMock(RequestInterface::class);
    }

    public function testGenerate()
    {
        $url = 'https://foo.bar/baz';

        $this->redirectionHeadersGenerator->expects($this->atLeastOnce())->method('generate')->willReturn([]);

        $result = $this->redirectionResponseGenerator->generate($this->request, $url, 301, '1.1');

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}
