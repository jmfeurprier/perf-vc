<?php

namespace perf\Vc;

/**
 *
 */
class RedirectExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
    }

    /**
     *
     */
    public function testGetUrl()
    {
        $this->url            = 'http://foo.bar/baz';
        $this->httpStatusCode = 123;

        $this->exception = new RedirectException($this->url, $this->httpStatusCode);

        $this->assertSame($this->url, $this->exception->getUrl());
    }

    /**
     *
     */
    public function testGetHttpStatusCode()
    {
        $this->url            = 'http://foo.bar/baz';
        $this->httpStatusCode = 123;

        $this->exception = new RedirectException($this->url, $this->httpStatusCode);

        $this->assertSame($this->httpStatusCode, $this->exception->getHttpStatusCode());
    }
}
