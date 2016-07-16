<?php

namespace perf\Vc\Response;

use perf\Vc\Routing\Route;

/**
 * Response builder interface.
 */
interface ResponseBuilderInterface
{

    /**
     *
     *
     * @param string $type
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setType($type);

    /**
     *
     *
     * @param int $code
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setHttpStatusCode($code);

    /**
     * Adds a HTTP header.
     *
     * @param string $header
     * @param string $value
     * @return ResponseBuilderInterface Fluent return.
     */
    public function addHeader($header, $value);

    /**
     * Adds a raw HTTP header.
     *
     * @param string $header
     * @return ResponseBuilderInterface Fluent return.
     */
    public function addRawHeader($header);

    /**
     *
     *
     * @param mixed $content
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setContent($content);

    /**
     *
     *
     * @param string $key
     * @param mixed  $value
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setVar($key, $value);

    /**
     *
     *
     * @param {string:mixed} $vars
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setVars(array $vars);

    /**
     *
     *
     * @param {string:mixed} $vars
     * @return ResponseBuilderInterface Fluent return.
     */
    public function addVars(array $vars);

    /**
     *
     *
     * @param string $key
     * @return ResponseBuilderInterface Fluent return.
     */
    public function unsetVar($key);

    /**
     *
     *
     * @param string $key
     * @param mixed  $value
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setSetting($key, $value);

    /**
     *
     *
     * @param {string:mixed} $settings
     * @return ResponseBuilderInterface Fluent return.
     */
    public function setSettings(array $settings);

    /**
     *
     *
     * @param Route $route
     * @return ResponseInterface
     */
    public function build(Route $route);
}
