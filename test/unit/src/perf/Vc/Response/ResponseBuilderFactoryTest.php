<?php

namespace perf\Vc\Response;

/**
 *
 */
class ResponseBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->httpStatusRepository = $this->getMock('perf\\Http\\Status\\HttpStatusRepository');
    }

    /**
     *
     */
    public function testCreate()
    {
        $driver = $this->getMockBuilder('perf\\Vc\\Response\\ResponseDriver')->disableOriginalConstructor()->getMock();

        $drivers = array(
            $driver,
        );

        $factory = new ResponseBuilderFactory($drivers, $this->httpStatusRepository);

        $result = $factory->create();

        $this->assertInstanceOf('perf\\Vc\\Response\\ResponseBuilderInterface', $result);
    }
}
