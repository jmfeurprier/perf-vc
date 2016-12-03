<?php

namespace perf\Vc\Response;

use perf\Source\Source;
use perf\Source\StringSource;
use perf\Vc\Routing\Route;

/**
 * Response driver.
 */
class ResponseDriver
{

    /**
     *
     *
     * @var ContentTransformerInterface[]
     */
    private $contentTransformers;

    /**
     * Settings.
     *
     * @var {string:mixed}
     */
    private $settings = array(
        'charset'      => null,
        'content-type' => null,
    );

    /**
     * Constructor.
     *
     * @param string                        $type
     * @param ContentTransformerInterface[] $contentTransformers
     * @param {string:mixed}                $settings
     */
    public function __construct($type, array $contentTransformers = array(), array $settings = array())
    {
        $this->type                = $type;
        $this->contentTransformers = $contentTransformers; // @xxx
        $this->settings            = array_replace($this->settings, $settings);
    }

    /**
     *
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     *
     * @param {string:null|string} $headers
     * @param {string:mixed}       $settings
     * @return {string:null|string}
     */
    public function generateHeaders(array $headers, array $settings)
    {
        $settings = array_replace($this->settings, $settings);

        $baseHeaders = array();
        if (null !== $settings['content-type']) {
            $header = $settings['content-type'];

            if (null !== $settings['charset']) {
                $header .= "; charset={$settings['charset']}";
            }

            $baseHeaders['Content-Type'] = $header;
        }

        return array_replace(
            $baseHeaders,
            $headers
        );
    }

    /**
     *
     *
     * @param mixed          $content
     * @param {string:mixed} $vars
     * @param {string:mixed} $settings
     * @param Route          $route
     * @return Source
     */
    public function generateContent($content, array $vars, array $settings, Route $route)
    {
        $settings = array_replace($this->settings, $settings);

        foreach ($this->contentTransformers as $transformer) {
            $content = $transformer->transform($route, $content, $settings, $vars);
        }

        if ($content instanceof Source) {
            return $content;
        }

        if ((null === $content) || is_string($content)) {
            return StringSource::create((string) $content);
        }

        throw new \RuntimeException('Unexpected content type.');
    }
}
