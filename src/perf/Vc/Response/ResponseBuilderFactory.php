<?php

namespace perf\Vc\Response;

use perf\Http\Status\HttpStatusRepository;
use perf\Vc\Routing\Route;

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
    private $drivers = array();

    /**
     *
     *
     * @var HttpStatusRepository
     */
    private $httpStatusRepository;

    /**
     * Constructor.
     *
     * @param ResponseDriver[]     $drivers
     * @param HttpStatusRepository $httpStatusRepository
     */
    public function __construct(array $drivers, HttpStatusRepository $httpStatusRepository)
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
