<?php

namespace perf\Vc\Response;

/**
 *
 */
class ResponseBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->route = $this->getMockBuilder('perf\\Vc\\Routing\\Route')->disableOriginalConstructor()->getMock();

        $this->httpStatusRepository = $this->getMock('perf\\Http\\Status\\HttpStatusRepository');
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No driver provided.
     */
    public function testConstructorWithoutDriverWillThrowException()
    {
        $drivers = array();

        new ResponseBuilder($drivers, $this->httpStatusRepository);
    }

    /**
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported response type.
     */
    public function testSetTypeWithUnsupportedTypeWillThrowException()
    {
        $driver = $this->getMockBuilder('perf\\Vc\\Response\\ResponseDriver')->disableOriginalConstructor()->getMock();
        $driver->expects($this->atLeastOnce())->method('getType')->willReturn('foo');

        $drivers = array(
            $driver,
        );

        $builder = new ResponseBuilder($drivers, $this->httpStatusRepository);

        $builder->setType('bar');
    }

    /**
     *
     */
    public function testBuildWithContent()
    {
        $type             = 'foo';
        $content          = 'bar';
        $vars             = array();
        $generatedContent = 'baz';

        $generatedContentSource = $this->getMock('perf\\Source\\Source');
        $generatedContentSource->expects($this->atLeastOnce())->method('getContent')->willReturn($generatedContent);

        $driver = $this->getMockBuilder('perf\\Vc\\Response\\ResponseDriver')->disableOriginalConstructor()->getMock();
        $driver->expects($this->atLeastOnce())->method('getType')->willReturn($type);
        $driver->expects($this->atLeastOnce())->method('generateHeaders')->willReturn(array());
        $driver->expects($this->atLeastOnce())->method('generateContent')->willReturn($generatedContentSource);

        $drivers = array(
            $driver,
        );

        $builder = new ResponseBuilder($drivers, $this->httpStatusRepository);

        $builder
            ->setContent($content)
        ;

        $result = $builder->build($this->route);

        $this->assertInstanceOf('perf\\Vc\\Response\\ResponseInterface', $result);
        $this->assertSame($generatedContent, $result->getContent());
        $this->assertCount(0, $result->getHeaders());
    }

    /**
     *
     */
    public function testBuildWithGeneratedHeaders()
    {
        $type             = 'foo';
        $content          = 'bar';
        $generatedContent = 'baz';
        $vars             = array();
        $generatedHeaders  = array(
            'abc' => 'def',
            'ghi' => 'jkl',
        );

        $generatedContentSource = $this->getMock('perf\\Source\\Source');

        $driver = $this->getMockBuilder('perf\\Vc\\Response\\ResponseDriver')->disableOriginalConstructor()->getMock();
        $driver->expects($this->atLeastOnce())->method('getType')->willReturn($type);
        $driver->expects($this->atLeastOnce())->method('generateHeaders')->willReturn($generatedHeaders);
        $driver->expects($this->atLeastOnce())->method('generateContent')->willReturn($generatedContentSource);

        $drivers = array(
            $driver,
        );

        $builder = new ResponseBuilder($drivers, $this->httpStatusRepository);

        $result = $builder->build($this->route);

        $this->assertInstanceOf('perf\\Vc\\Response\\ResponseInterface', $result);
        $this->assertCount(2, $result->getHeaders());
        $this->assertContains('abc: def', $result->getHeaders());
        $this->assertContains('ghi: jkl', $result->getHeaders());
    }
}
