<?php

namespace perf\Vc;

use RuntimeException;

/**
 * HTTP request populator.
 */
class RequestPopulator
{
    /**
     * Server values container.
     * Temporary property.
     *
     * @var {string:mixed}
     */
    private $server;

    /**
     * Static constructor.
     *
     * @return RequestPopulator
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Returns a new HTTP request instance, populated with global values.
     *
     * @return Request
     *
     * @throws RuntimeException
     */
    public function populate()
    {
        $query        = isset($_GET) ? $_GET : array();
        $cookies      = isset($_COOKIE) ? $_COOKIE : array();
        $this->server = isset($_SERVER) ? $_SERVER : array();

        $method = $this->getMethod();
        $path   = $this->getPath();
        $post   = $this->getPost($method);

        return new Request($method, $path, $query, $post, $cookies, $this->server);
    }

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    private function getMethod()
    {
        if (array_key_exists('REQUEST_METHOD', $this->server)) {
            return $this->server['REQUEST_METHOD'];
        }

        throw new RuntimeException('Failed to retrieve HTTP method.');
    }

    /**
     * @return string
     *
     * @throws RuntimeException
     */
    private function getPath()
    {
        // Adding host/domain helps parsing paths from malformed URLs.
        if (array_key_exists('HTTP_HOST', $this->server)) {
            $url = 'http://' . $this->server['HTTP_HOST'];
        } else {
            $url = '';
        }

        if (array_key_exists('REDIRECT_URL', $this->server)) {
            $url .= $this->server['REDIRECT_URL'];
        } elseif (array_key_exists('REQUEST_URI', $this->server)) {
            $url .= $this->server['REQUEST_URI'];
        } else {
            throw new RuntimeException('Failed to retrieve HTTP request path.');
        }

        $path = parse_url($url, \PHP_URL_PATH);

        if (false === $path) {
            throw new RuntimeException("Failed to retrieve HTTP request path from URL '{$url}'.");
        }

        return $path;
    }

    /**
     * @param string $method
     *
     * @return array
     */
    private function getPost($method)
    {
        $post = array();

        if ('POST' === $method) {
            $post = $_POST;

            if (isset($_FILES)) {
                foreach ($_FILES as $key => $file) {
                    $fileKey = "@{$key}";

                    $post[$fileKey] = $file;
                }
            }
        } elseif (in_array($method, array('PUT', 'DELETE'), true)) {
            parse_str(file_get_contents("php://input"), $post);
        }

        return $post;
    }
}