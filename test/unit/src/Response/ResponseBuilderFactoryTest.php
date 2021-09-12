<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ResponseBuilderFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        $this->httpStatusRepository = $this->createMock(HttpStatusRepositoryInterface::class);
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
