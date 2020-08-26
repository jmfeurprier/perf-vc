<?php

namespace perf\Vc\Response;

use perf\HttpStatus\Exception\HttpStatusException;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Source\SourceInterface;
use perf\Source\StringSource;
use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;
use perf\Vc\Routing\RouteInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    private HttpStatusRepositoryInterface $httpStatusRepository;

    private ViewLocatorInterface $templateLocator;

    private ViewRendererInterface $templateRenderer;

    private ?int $httpStatusCode;

    /**
     * @var Header[]
     */
    private array $headers = [];

    /**
     * @var string|SourceInterface
     */
    private $content = '';

    /**
     * @var {string:mixed}
     */
    private array $vars = [];

    public function __construct(
        HttpStatusRepositoryInterface $httpStatusRepository,
        ViewLocatorInterface $templateLocator,
        ViewRendererInterface $templateRenderer
    ) {
        $this->httpStatusRepository = $httpStatusRepository;
        $this->templateLocator      = $templateLocator;
        $this->templateRenderer     = $templateRenderer;
    }

    public function setHttpStatusCode(int $code): self
    {
        $this->httpStatusCode = $code;

        return $this;
    }

    public function addHeader(string $header, string $value)
    {
        $this->headers[] = new Header($header, $value);

        return $this;
    }

    public function addRawHeader(string $header): self
    {
        $this->headers[] = new Header($header);

        return $this;
    }

    /**
     * @param string|SourceInterface $content
     *
     * @return ResponseBuilder
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param {string:mixed} $vars
     *
     * @return ResponseBuilder
     */
    public function setVars(array $vars): self
    {
        $this->vars = [];

        $this->addVars($vars);

        return $this;
    }

    /**
     * @param {string:mixed} $vars
     *
     * @return ResponseBuilder
     */
    public function addVars(array $vars): self
    {
        foreach ($vars as $key => $value) {
            $this->setVar($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return ResponseBuilder
     */
    public function setVar(string $key, $value): self
    {
        $this->vars[$key] = $value;

        return $this;
    }

    public function unsetVar(string $key): self
    {
        unset($this->vars[$key]);

        return $this;
    }

    public function renderTemplate(RouteInterface $route, array $vars = []): void
    {
        $templatePath = $this->templateLocator->locate($route);

        $this->content = $this->templateRenderer->render($templatePath, $vars);
    }

    /**
     * @param RouteInterface $route
     *
     * @return ResponseInterface
     *
     * @throws VcException
     */
    public function build(RouteInterface $route): ResponseInterface
    {
        return new Response(
            $this->buildHeaders(),
            $this->buildContent()
        );
    }

    /**
     * @return array
     *
     * @throws VcException
     */
    private function buildHeaders(): array
    {
        $headers = [];

        if (!empty($this->httpStatusCode)) {
            try {
                $httpStatus = $this->httpStatusRepository->get($this->httpStatusCode);
            } catch (HttpStatusException $e) {
                throw new VcException('HTTP status code not found.', 0, $e);
            }

            $headers[] = new Header($httpStatus->toHeader());
        }

        foreach ($this->headers as $header) {
            $headers[] = $header;
        }

        return $headers;
    }

    /**
     * @return SourceInterface
     *
     * @throws VcException
     */
    private function buildContent(): SourceInterface
    {
        if (is_string($this->content)) {
            return StringSource::create($this->content);
        }

        if ($this->content instanceof SourceInterface) {
            return $this->content;
        }

        throw new VcException('Invalid content type (expected SourceInterface or string types.');
    }
}
