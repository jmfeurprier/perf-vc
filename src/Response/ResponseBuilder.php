<?php

namespace perf\Vc\Response;

use perf\HttpStatus\Exception\HttpStatusException;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Source\SourceInterface;
use perf\Source\StringSource;
use perf\Vc\Exception\VcException;
use perf\Vc\Header\Header;
use perf\Vc\Header\HeaderCollection;
use perf\Vc\Response\Transformation\Transformation;
use perf\Vc\Response\Transformation\TransformerRepositoryInterface;
use perf\Vc\Routing\RouteInterface;
use perf\Vc\View\ViewLocatorInterface;
use perf\Vc\View\ViewRendererInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    private HttpStatusRepositoryInterface $httpStatusRepository;

    private ViewLocatorInterface $templateLocator;

    private ViewRendererInterface $templateRenderer;

    private TransformerRepositoryInterface $transformerRepository;

    private ?int $httpStatusCode;

    private HeaderCollection $headers;

    /**
     * @var string|SourceInterface
     */
    private $content = '';

    private KeyValueCollection $vars;

    /**
     * @var Transformation[]
     */
    private array $transformations = [];

    public function __construct(
        HttpStatusRepositoryInterface $httpStatusRepository,
        ViewLocatorInterface $templateLocator,
        ViewRendererInterface $templateRenderer,
        TransformerRepositoryInterface $transformerRepository,
        array $vars = []
    ) {
        $this->httpStatusRepository  = $httpStatusRepository;
        $this->templateLocator       = $templateLocator;
        $this->templateRenderer      = $templateRenderer;
        $this->transformerRepository = $transformerRepository;
        $this->headers               = new HeaderCollection();
        $this->vars                  = new KeyValueCollection($vars);
    }

    public function setHttpStatusCode(int $code): self
    {
        $this->httpStatusCode = $code;

        return $this;
    }

    public function addHeader(string $header, string $value)
    {
        $this->headers->add(new Header($header, $value));

        return $this;
    }

    public function addRawHeader(string $header): self
    {
        $this->headers->add(new Header($header));

        return $this;
    }

    public function headers(): HeaderCollection
    {
        return $this->headers;
    }

    /**
     * @param string|SourceInterface $content
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setVars(array $vars): self
    {
        $this->vars->setMany($vars);

        return $this;
    }

    /**
     * @param mixed  $value
     */
    public function setVar(string $key, $value): self
    {
        $this->vars->set($key, $value);

        return $this;
    }

    public function vars(): KeyValueCollection
    {
        return $this->vars;
    }

    public function addTransformation(string $transformerClass, array $parameters = []): self
    {
        $transformer = $this->transformerRepository->get($transformerClass);

        $this->transformations[] = new Transformation($transformer, $parameters);

        return $this;
    }

    public function renderTemplate(RouteInterface $route, array $vars = []): void
    {
        $vars = array_merge($this->vars->getAll(), $vars);

        $templatePath = $this->templateLocator->locate($route);

        $this->content = $this->templateRenderer->render($templatePath, $vars);
    }

    /**
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
     * @throws VcException
     */
    private function buildHeaders(): array
    {
        $headers = clone $this->headers;

        foreach ($this->transformations as $transformation) {
            $headers = $transformation->getTransformer()
                ->transformHeaders(
                    $headers,
                    $this->vars->getAll(),
                    $transformation->getParameters()
                )
            ;
        }

        $headers = $headers->getAll();

        if (!empty($this->httpStatusCode)) {
            try {
                $httpStatus = $this->httpStatusRepository->get($this->httpStatusCode);
            } catch (HttpStatusException $e) {
                throw new VcException('HTTP status code not found.', 0, $e);
            }

            array_unshift($headers, new Header($httpStatus->toHeader()));
        }

        return $headers;
    }

    /**
     * @throws VcException
     */
    private function buildContent(): SourceInterface
    {
        $content = $this->content;

        foreach ($this->transformations as $transformation) {
            $content = $transformation->getTransformer()
                ->transformContent(
                    $content,
                    $this->vars->getAll(),
                    $transformation->getParameters()
                )
            ;
        }

        if (is_string($content)) {
            return StringSource::create($content);
        }

        if ($content instanceof SourceInterface) {
            return $content;
        }

        throw new VcException('Invalid content type (expected SourceInterface or string types.');
    }
}
