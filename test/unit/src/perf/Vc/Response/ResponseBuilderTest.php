<?php

namespace perf\Vc\Response;

use PHPUnit\Framework\TestCase;

class ResponseBuilderTest extends TestCase
{
    protected function setUp(): void
    {
        $this->route = $this->createMock('perf\\Vc\\Routing\\Route');

        $this->httpStatusRepository = $this->createMock('perf\\Http\\Status\\HttpStatusRepository');
    }

    public function testConstructorWithoutDriverWillThrowException()
    {
        $drivers = [];
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No driver provided.');

        new ResponseBuilder($drivers, $this->httpStatusRepository);
    }

    public function testSetTypeWithUnsupportedTypeWillThrowException()
    {
        $driver = $this->createMock('perf\\Vc\\Response\\ResponseDriver');
        $driver->expects($this->atLeastOnce())->method('getType')->willReturn('foo');

        $drivers = [
            $driver,
        ];

        $builder = new ResponseBuilder($drivers, $this->httpStatusRepository);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Unsupported response type.');
        $builder->setType('bar');
    }

    public function testBuildWithContent()
    {
        $type             = 'foo';
        $content          = 'bar';
        $generatedContent = 'baz';

        $generatedContentSource = $this->createMock('perf\\Source\\Source');
        $generatedContentSource->expects($this->atLeastOnce())->method('getContent')->willReturn($generatedContent);

        $driver = $this->createMock('perf\\Vc\\Response\\ResponseDriver');
        $driver->expects($this->atLeastOnce())->method('getType')->willReturn($type);
        $driver->expects($this->atLeastOnce())->method('generateHeaders')->willReturn([]);
        $driver->expects($this->atLeastOnce())->method('generateContent')->willReturn($generatedContentSource);

        $drivers = [
            $driver,
        ];

        $builder = new ResponseBuilder($drivers, $this->httpStatusRepository);

        $builder
            ->setContent($content)
        ;

        $result = $builder->build($this->route);

        $this->assertInstanceOf('perf\\Vc\\Response\\ResponseInterface', $result);
        $this->assertSame($generatedContent, $result->getContent());
        $this->assertCount(0, $result->getHeaders());
    }

    public function testBuildWithGeneratedHeaders()
    {
        $type             = 'foo';
        $content          = 'bar';
        $generatedContent = 'baz';
        $vars             = [];
        $generatedHeaders = [
            'abc' => 'def',
            'ghi' => 'jkl',
        ];

        $generatedContentSource = $this->createMock('perf\\Source\\Source');

        $driver = $this->createMock('perf\\Vc\\Response\\ResponseDriver');
        $driver->expects($this->atLeastOnce())->method('getType')->willReturn($type);
        $driver->expects($this->atLeastOnce())->method('generateHeaders')->willReturn($generatedHeaders);
        $driver->expects($this->atLeastOnce())->method('generateContent')->willReturn($generatedContentSource);

        $drivers = [
            $driver,
        ];

        $builder = new ResponseBuilder($drivers, $this->httpStatusRepository);

        $result = $builder->build($this->route);

        $this->assertInstanceOf('perf\\Vc\\Response\\ResponseInterface', $result);
        $this->assertCount(2, $result->getHeaders());
        $this->assertContains('abc: def', $result->getHeaders());
        $this->assertContains('ghi: jkl', $result->getHeaders());
    }
}
