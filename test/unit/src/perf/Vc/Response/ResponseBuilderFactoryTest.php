<?php

namespace perf\Vc\Response;

use PHPUnit\Framework\TestCase;

class ResponseBuilderFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->httpStatusRepository = $this->createMock('perf\\Http\\Status\\HttpStatusRepository');
    }

    public function testCreate()
    {
        $driver = $this->createMock('perf\\Vc\\Response\\ResponseDriver');

        $drivers = array(
            $driver,
        );

        $factory = new ResponseBuilderFactory($drivers, $this->httpStatusRepository);

        $result = $factory->create();

        $this->assertInstanceOf('perf\\Vc\\Response\\ResponseBuilderInterface', $result);
    }
}
