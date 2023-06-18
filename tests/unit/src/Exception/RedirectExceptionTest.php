<?php

namespace perf\Vc\Exception;

use perf\Vc\Redirection\PathRedirection;
use perf\Vc\Redirection\RedirectionInterface;
use perf\Vc\Redirection\RouteRedirection;
use perf\Vc\Redirection\UrlRedirection;
use PHPUnit\Framework\TestCase;

class RedirectExceptionTest extends TestCase
{
    public function testCreateFromRoute(): void
    {
        $exception = RedirectException::createFromRoute('Module', 'Action', ['foo' => 'bar'], 302);

        $this->assertInstanceOf(RouteRedirection::class, $exception->getRedirection());
        $this->assertSame(302, $exception->getRedirection()->getHttpStatusCode());
    }

    public function testCreateFromPath(): void
    {
        $exception = RedirectException::createFromPath('foo/bar', 302);

        $this->assertInstanceOf(PathRedirection::class, $exception->getRedirection());
        $this->assertSame(302, $exception->getRedirection()->getHttpStatusCode());
    }

    public function testCreateFromUrl(): void
    {
        $exception = RedirectException::createFromUrl('https://foo.bar/baz?qux=123', 302);

        $this->assertInstanceOf(UrlRedirection::class, $exception->getRedirection());
        $this->assertSame(302, $exception->getRedirection()->getHttpStatusCode());
    }

    public function testGetRedirection(): void
    {
        $redirection = $this->createMock(RedirectionInterface::class);

        $exception = new RedirectException($redirection);

        $this->assertSame($redirection, $exception->getRedirection());
    }
}
