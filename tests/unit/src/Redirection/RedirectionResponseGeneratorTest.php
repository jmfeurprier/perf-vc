<?php

namespace perf\Vc\Redirection;

use perf\Vc\Request\RequestInterface;
use perf\Vc\Response\ResponseInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RedirectionResponseGeneratorTest extends TestCase
{
    private MockObject&RedirectionHeadersGeneratorInterface $redirectionHeadersGenerator;

    private RedirectionResponseGenerator $redirectionResponseGenerator;

    private MockObject&RequestInterface $request;

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
