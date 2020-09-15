<?php

namespace perf\Vc\Request;

use perf\Vc\Exception\VcException;
use PHPUnit\Framework\TestCase;

class RequestPopulatorTest extends TestCase
{
    private array $channelValues = [
        'get'    => [],
        'post'   => [],
        'cookie' => [],
        'files'  => [],
        'server' => [],
    ];

    private RequestInterface $result;

    public function testPopulateWillRetrieveMethod()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'OPTIONS',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->whenPopulate();

        $this->assertSame('OPTIONS', $this->result->getMethod());
    }

    public function testPopulateWithoutServerRequestMethodWillThrowException()
    {
        $this->givenServerValues(
            [
                'REQUEST_URI' => '/',
                'SERVER_NAME' => 'localhost',
                'SERVER_PORT' => 80,
            ]
        );

        $this->expectException(VcException::class);
        $this->expectExceptionMessage('Failed to retrieve HTTP method.');

        $this->whenPopulate();
    }

    public function testPopulateWillRetrieveHost()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'OPTIONS',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'foo.bar',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->whenPopulate();

        $this->assertSame('foo.bar', $this->result->getHost());
    }

    public function testPopulateWithoutServerRequestHostWillThrowException()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'OPTIONS',
                'REQUEST_URI'    => '/',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->expectException(VcException::class);
        $this->expectExceptionMessage('Failed to retrieve HTTP host.');

        $this->whenPopulate();
    }

    public function testPopulateWillRetrievePort()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'OPTIONS',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'foo.bar',
                'SERVER_PORT'    => 123,
            ]
        );

        $this->whenPopulate();

        $this->assertSame(123, $this->result->getPort());
    }

    public function testPopulateWithoutServerRequestPortWillThrowException()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'OPTIONS',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'localhost',
            ]
        );

        $this->expectException(VcException::class);
        $this->expectExceptionMessage('Failed to retrieve HTTP port.');

        $this->whenPopulate();
    }

    public function testPopulateWillRetrieveHttpsTransport()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/',
                'HTTPS'          => 'on',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 443,
            ]
        );

        $this->whenPopulate();

        $this->assertSame('https', $this->result->getTransport());
    }

    public function testPopulateWillRetrieveHttpTransport()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->whenPopulate();

        $this->assertSame('http', $this->result->getTransport());
    }

    public function testPopulateWithServerRequestUriWillRetrievePath()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/foo/bar.txt',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->whenPopulate();

        $this->assertSame('/foo/bar.txt', $this->result->getPath());
    }

    public function testPopulateWithServerRedirectUrlWillRetrievePath()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/foo/bar.txt',
                'REDIRECT_URL'   => '/baz/qux.txt',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->whenPopulate();

        $this->assertSame('/baz/qux.txt', $this->result->getPath());
    }

    public function testPopulateWithoutServerUrlWillThrowException()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->expectException(VcException::class);
        $this->expectExceptionMessage('Failed to retrieve HTTP request path.');

        $this->whenPopulate();
    }

    public function testPopulateWithInvalidServerUrlWillThrowException()
    {
        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => ':1',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );

        $this->expectException(VcException::class);
        $this->expectExceptionMessage("Failed to retrieve HTTP request path from URL");

        $this->whenPopulate();
    }

    public function testPopulateWillRetrieveQueryValues()
    {
        $getValues = [
            'foo' => 'bar',
        ];

        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );
        $this->givenGetValues($getValues);

        $this->whenPopulate();

        $this->assertSame($getValues, $this->result->getQuery()->getAll());
    }

    public function testPopulateWillRetrievePostValues()
    {
        $postValues = [
            'foo' => 'bar',
        ];

        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'POST',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );
        $this->givenPostValues($postValues);

        $this->whenPopulate();

        $this->assertSame($postValues, $this->result->getPost()->getAll());
    }

    public function testPopulateWillRetrieveCookieValues()
    {
        $cookieValues = [
            'foo' => 'bar',

        ];

        $this->givenServerValues(
            [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI'    => '/',
                'SERVER_NAME'    => 'localhost',
                'SERVER_PORT'    => 80,
            ]
        );
        $this->givenCookieValues($cookieValues);

        $this->whenPopulate();

        $this->assertSame($cookieValues, $this->result->getCookies()->getAll());
    }

    public function testPopulateWillRetrieveServerValues()
    {
        $serverValues = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/',
            'SERVER_NAME'    => 'localhost',
            'SERVER_PORT'    => 80,
        ];

        $this->givenServerValues($serverValues);

        $this->whenPopulate();

        $this->assertSame($serverValues, $this->result->getServer()->getAll());
    }

    private function givenServerValues(array $values): void
    {
        $this->givenChannelValues('server', $values);
    }

    private function givenGetValues(array $values): void
    {
        $this->givenChannelValues('get', $values);
    }

    private function givenPostValues(array $values): void
    {
        $this->givenChannelValues('post', $values);
    }

    private function givenCookieValues(array $values): void
    {
        $this->givenChannelValues('cookie', $values);
    }

    private function givenChannelValues(string $channel, array $values): void
    {
        foreach ($values as $key => $value) {
            $this->channelValues[$channel][$key] = $value;
        }
    }

    private function whenPopulate(): void
    {
        $populator = new RequestPopulator(
            $this->channelValues['get'],
            $this->channelValues['post'],
            $this->channelValues['cookie'],
            $this->channelValues['files'],
            $this->channelValues['server']
        );

        $this->result = $populator->populate();
    }
}
