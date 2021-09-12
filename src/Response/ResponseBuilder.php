<?php

namespace perf\Vc\Response;

use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Vc\Routing\Route;

/**
 * Response builder.
 *
 */
class ResponseBuilder implements ResponseBuilderInterface
{

    /**
     *
     *
     * @var {string:ResponseDriver}
     */
    private $drivers = [];

    /**
     *
     *
     * @var HttpStatusRepositoryInterface
     */
    private $httpStatusRepository;

    /**
     *
     *
     * @var string
     */
    private $type;

    /**
     *
     *
     * @var null|int
     */
    private $httpStatusCode;

    /**
     * HTTP headers.
     *
     * @var {string:mixed}
     */
    private $headers = [];

    /**
     *
     *
     * @var mixed
     */
    private $content;

    /**
     *
     *
     * @var {string:mixed}
     */
    private $vars = [];

    /**
     *
     *
     * @var {string:mixed}
     */
    private $settings = [];

    /**
     * Constructor.
     *
     * @param ResponseDriver[]              $drivers
     * @param HttpStatusRepositoryInterface $httpStatusRepository
     */
    public function __construct(array $drivers, HttpStatusRepositoryInterface $httpStatusRepository)
    {
        if (empty($drivers)) {
            throw new \InvalidArgumentException('No driver provided.');
        }

        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }

        $this->type                 = reset($drivers)->getType();
        $this->httpStatusRepository = $httpStatusRepository;
    }

    /**
     *
     *
     * @param ResponseDriver $driver
     *
     * @return void
     */
    private function addDriver(ResponseDriver $driver)
    {
        $this->drivers[$driver->getType()] = $driver;
    }

    /**
     *
     *
     * @param string $type
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setType($type)
    {
        if (!array_key_exists($type, $this->drivers)) {
            throw new \InvalidArgumentException('Unsupported response type.');
        }

        $this->type = $type;

        return $this;
    }

    /**
     *
     *
     * @param int $code
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setHttpStatusCode($code)
    {
        $this->httpStatusCode = $code;

        return $this;
    }

    /**
     * Adds a HTTP header.
     *
     * @param string $header
     * @param string $value
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function addHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Adds a raw HTTP header.
     *
     * @param string $header
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function addRawHeader($header)
    {
        $this->headers[$header] = null;

        return $this;
    }

    /**
     *
     *
     * @param mixed $content
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     *
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setVar($key, $value)
    {
        $this->vars[$key] = $value;

        return $this;
    }

    /**
     *
     *
     * @param {string:mixed} $vars
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     *
     *
     * @param {string:mixed} $vars
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function addVars(array $vars)
    {
        $this->vars = array_replace($this->vars, $vars);

        return $this;
    }

    /**
     *
     *
     * @param string $key
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function unsetVar($key)
    {
        unset($this->vars[$key]);

        return $this;
    }

    /**
     *
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setSetting($key, $value)
    {
        $this->settings[$key] = $value;

        return $this;
    }

    /**
     *
     *
     * @param {string:mixed} $settings
     *
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     *
     *
     * @param Route $route
     *
     * @return ResponseInterface
     */
    public function build(Route $route)
    {
        $driver = $this->drivers[$this->type];

        $headers = $driver->generateHeaders($this->headers, $this->settings);

        if (null !== $this->httpStatusCode) {
            $httpStatus = $this->httpStatusRepository->get($this->httpStatusCode);

            $headers[$httpStatus->toHeader()] = null;
        }

        $content = $driver->generateContent($this->content, $this->vars, $this->settings, $route);

        return new Response($headers, $content);
    }
}
