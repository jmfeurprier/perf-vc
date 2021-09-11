<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;

/**
 * Response builder factory.
 *
 */
class ResponseBuilderFactory implements ResponseBuilderFactoryInterface
{

    /**
     *
     *
     * @var ResponseDriver[]
     */
    private $drivers = [];

    /**
     *
     *
     * @var HttpStatusRepositoryInterface
     */
    private $httpStatusRepository;

    /**
     * Constructor.
     *
     * @param ResponseDriver[]              $drivers
     * @param HttpStatusRepositoryInterface $httpStatusRepository
     */
    public function __construct(array $drivers, HttpStatusRepositoryInterface $httpStatusRepository)
    {
        $this->drivers              = $drivers; // @todo
        $this->httpStatusRepository = $httpStatusRepository;
    }

    /**
     *
     *
     * @return ResponseBuilder
     */
    public function create()
    {
        return new ResponseBuilder($this->drivers, $this->httpStatusRepository);
    }
}
