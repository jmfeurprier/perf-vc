<?php

namespace perf\Vc\Request;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    private string $method = '';

    private Request $request;

    public function testIsMethodGetReturnsTrue(): void
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodGet());
    }

    public function testIsMethodGetReturnsFalse(): void
    {
        $this->givenMethod('POST');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodGet());
    }

    public function testIsMethodPostReturnsTrue(): void
    {
        $this->givenMethod('POST');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodPost());
    }

    public function testIsMethodPostReturnsFalse(): void
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodPost());
    }

    public function testIsMethodPutReturnsTrue(): void
    {
        $this->givenMethod('PUT');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodPut());
    }

    public function testIsMethodPutReturnsFalse(): void
    {
        $this->givenMethod('GET');

        $this->whenInstantiate();

        $this->assertFalse($this->request->isMethodPut());
    }

    public function testIsMethodDeleteReturnsTrue(): void
    {
        $this->givenMethod('DELETE');

        $this->whenInstantiate();

        $this->assertTrue($this->request->isMethodDelete());
    }

    public function testIsMethodDeleteReturnsFalse(): void
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
