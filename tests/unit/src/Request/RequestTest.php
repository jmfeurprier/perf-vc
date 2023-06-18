<?php

namespace perf\Vc\Request;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private string $method = '';

    private Request $request;

    public function testIsMethodGetReturnsTrue()
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodGet());
    }

    public function testIsMethodGetReturnsFalse()
    {
        $this->givenMethod('POST');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodGet());
    }

    public function testIsMethodPostReturnsTrue()
    {
        $this->givenMethod('POST');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodPost());
    }

    public function testIsMethodPostReturnsFalse()
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodPost());
    }

    public function testIsMethodPutReturnsTrue()
    {
        $this->givenMethod('PUT');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodPut());
    }

    public function testIsMethodPutReturnsFalse()
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodPut());
    }

    public function testIsMethodDeleteReturnsTrue()
    {
        $this->givenMethod('DELETE');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodDelete());
    }

    public function testIsMethodDeleteReturnsFalse()
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodDelete());
    }

    private function givenMethod(string $method): void
    {
        $this->method = $method;
    }

    private function whenInstantiate(): void
    {
        $this->request = new Request(
            $this->method,
            'https',
            'localhost',
            123,
            '/',
            new RequestChannel([]),
            new RequestChannel([]),
            new RequestChannel([]),
            new RequestChannel([])
        );
    }
}
