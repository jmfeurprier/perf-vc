<?php

namespace perf\Vc\Response;

/**
 *
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testGetContent()
    {
        $headers = array();
        $content = 'foo';

        $contentSource = $this->getMock('perf\\Source\\Source');
        $contentSource->expects($this->atLeastOnce())->method('getContent')->willReturn($content);

        $response = new Response($headers, $contentSource);

        $this->assertSame($content, $response->getContent());
    }

    /**
     *
     */
    public function testGetHeadersWithoutHeaders()
    {
        $headers = array();

        $contentSource = $this->getMock('perf\\Source\\Source');

        $response = new Response($headers, $contentSource);

        $this->assertEmpty($response->getHeaders());
    }
}
