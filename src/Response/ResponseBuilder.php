<?php

namespace perf\Vc\Response;

use perf\HttpStatus\Exception\HttpStatusException;
use perf\HttpStatus\HttpStatusRepositoryInterface;
use perf\Source\SourceInterface;
use perf\Source\StringSource;
use perf\Vc\Exception\TransformerNotFoundException;
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
    private ?int $httpStatusCode = null;

    private readonly HeaderCollection $headers;

    private string|SourceInterface $content = '';

    private readonly KeyValueCollection $vars;

    /**
     * @var Transformation[]
     */
    private array $transformations = [];

    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private readonly HttpStatusRepositoryInterface $httpStatusRepository,
        private readonly ViewLocatorInterface $templateLocator,
        private readonly ViewRendererInterface $templateRenderer,
        private readonly TransformerRepositoryInterface $transformerRepository,
        array $vars = []
    ) {
        $this->headers = new HeaderCollection();
        $this->vars    = new KeyValueCollection($vars);
    }

    public function setHttpStatusCode(int $code): self
    {
        $this->httpStatusCode = $code;

        return $this;
    }

    public function addHeader(
        string $header,
        string $value
    ): self {
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

    public function setContent(string|SourceInterface $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setVars(array $vars): self
    {
        $this->vars->setMany($vars);

        return $this;
    }

    public function setVar(
        string $key,
        mixed $value
    ): self {
        $this->vars->set($key, $value);

        return $this;
    }

    public function vars(): KeyValueCollection
    {
        return $this->vars;
    }

    /**
     * @throws TransformerNotFoundException
     */
    public function addTransformation(
        string $transformerClass,
        array $parameters = []
    ): self {
        $transformer = $this->transformerRepository->get($transformerClass);

        $this->transformations[] = new Transformation($transformer, $parameters);

        return $this;
    }

    public function renderTemplate(
        RouteInterface $route,
        array $vars = []
    ): void {
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
     * @return Header[]
     *
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
