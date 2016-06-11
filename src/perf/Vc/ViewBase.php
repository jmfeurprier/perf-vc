<?php

namespace perf\Vc;

/**
 * View.
 *
 */
abstract class ViewBase implements ViewInterface
{

    /**
     * Path to the view file.
     *
     * @var string
     */
    private $viewPath;

    /**
     * View parameters.
     *
     * @var {string:mixed}
     */
    private $parameters = array();

    /**
     * Parent view being extended.
     *
     * @var null|View
     */
    private $extendedView;

    /**
     * Unique identifier of slot being populated.
     *
     * @var null|string
     */
    private $currentSlotId;

    /**
     * Slots and their content.
     *
     * @var {string:string}
     */
    private $slots = array();

    /**
     *
     *
     * @var EscaperInterface
     */
    private $escaper;

    /**
     *
     *
     * @var bool
     */
    private $autoEscape = true;

    /**
     * Constructor.
     *
     * @param string         $viewPath   Path to view file.
     * @param {string:mixed} $parameters Optional view parameters.
     */
    public function __construct($viewPath, array $parameters = array())
    {
        $this->viewPath = $viewPath;

        $this->setParameters($parameters);

        // @xxx
        $this->setEscaper(new HtmlEscaper());
    }

    /**
     *
     *
     * @param EscaperInterface $escaper
     * @return void
     */
    public function setEscaper(EscaperInterface $escaper)
    {
        $this->escaper = $escaper;
    }

    /**
     *
     *
     * @param {string:mixed} $parameters
     * @return void
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     *
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setParameter($name, $value)
    {
        $this->parameter[$name] = $value;
    }

    /**
     * Returns the path to the view file.
     *
     * @return string
     */
    protected function getViewPath()
    {
        return $this->viewPath;
    }

    /**
     * Tells whether a view parameter is set or not.
     * Magic method.
     *
     * @param string $name Name of the parameter.
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->parameters[$name]);
    }

    /**
     * Sets a view parameter.
     * Magic method.
     *
     * @param string $name Name of the parameter.
     * @param mixed $value Value of the parameter.
     * @return void
     */
    public function __set($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Unsets a view parameter.
     * Magic method.
     *
     * @param string $name Name of the parameter.
     * @return void
     */
    public function __unset($name)
    {
        unset($this->parameters[$name]);
    }

    /**
     * Returns the value of a view parameter.
     * Magic method.
     *
     * @param string $name Name of the parameter.
     * @return mixed
     * @throws \DomainException
     */
    public function &__get($name)
    {
        if (array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }

        throw new \DomainException("Parameter '{$name}' does not exist.");
    }

    /**
     * Builds the view content into a string.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function fetch()
    {
        $outputBufferringStartLevel = ob_get_level();

        ob_start();

        try {
            $this->includeViewFile();
        } catch (\Exception $e) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            $message = "Failed to render view ({$this->viewPath}). << {$e->getMessage()}";

            throw new \RuntimeException($message, 0, $e);
        }

        if (null !== $this->currentSlotId) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            $message = "Failed to render view ({$this->viewPath}): slot '{$this->currentSlotId}' not ended.";

            $this->currentSlotId = null;

            throw new \RuntimeException($message);
        }

        if ($this->extendedView) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            return $this->extendedView->fetch();
        }

        return ob_get_clean();
    }

    /**
     *
     *
     * @return void
     * @throws \Exception
     */
    abstract protected function includeViewFile();

    /**
     *
     *
     * @param int $outputBufferringStartLevel
     * @return void
     * @throws \RuntimeException
     */
    private function fixOutputBufferringStack($outputBufferringStartLevel)
    {
        if (ob_get_level() < $outputBufferringStartLevel) {
            throw new \RuntimeException('Failed to fix output bufferingf stack.');
        }

        while (ob_get_level() > $outputBufferringStartLevel) {
            ob_end_clean();
        }
    }

    /**
     * Extends another view.
     *
     * @param string $extendedViewPath Path to view file to extend.
     * @return void
     * @throws \RuntimeException
     */
    protected function extend($extendedViewPath)
    {
        if (null !== $this->extendedView) {
            throw new \RuntimeException('A view is already being extended.');
        }

        $this->extendedView = new View($extendedViewPath, $this->parameters);
        $this->extendedView->slots = $this->slots;
    }

    /**
     *
     *
     * @param string $slotId Slot unique identifier.
     * @return void
     * @throws \RuntimeException
     */
    protected function beginSlot($slotId)
    {
        if (!$this->extendedView) {
            throw new \RuntimeException("Cannot begin slot: not extending a view.");
        }

        if (null !== $this->currentSlotId) {
            throw new \RuntimeException("Cannot begin slot: a slot is already begun.");
        }

        $this->currentSlotId = $slotId;

        ob_start();
    }

    /**
     *
     *
     * @return void
     * @throws \RuntimeException
     */
    protected function endSlot()
    {
        if (null === $this->currentSlotId) {
            throw new \RuntimeException('Cannot end slot: no slot is begun.');
        }

        $this->extendedView->setSlotContent($this->currentSlotId, ob_get_clean());

        $this->currentSlotId = null;
    }

    /**
     * Sets slot content.
     *
     * @param string $slotId
     * @param string $content
     * @return void
     */
    private function setSlotContent($slotId, $content)
    {
        $this->slots[$slotId] = $content;

        if ($this->extendedView) {
            $this->extendedView->setSlotContent($slotId, $content);
        }
    }

    /**
     * Renders slot content, if defined.
     *
     * @param string $slotId Slot unique identifier.
     * @return void
     */
    protected function renderSlot($slotId)
    {
        if (array_key_exists($slotId, $this->slots)) {
            echo $this->slots[$slotId];
        }
    }

    /**
     * Includes another view file.
     *
     * @param string $subViewPath Path to sub-view.
     * @param {string:mixed} $childParameters Sub-view parameters.
     * @return void
     * @throws \RuntimeException
     */
    protected function embed($subViewPath, array $childParameters = array())
    {
        $subViewFullPath = dirname($this->viewPath) . '/' . $subViewPath;

        $parameters = array_merge(
            $this->parameters,
            $childParameters
        );

        $subView = new View($subViewFullPath, $parameters);

        try {
            echo $subView->getContent();
        } catch (\Exception $e) {
            $message = "Failed to embed sub-view at '{$subView->viewPath}'. << {$e->getMessage()}";

            throw new \RuntimeException($message, 0, $e);
        }
    }

    /**
     *
     *
     * @param bool $autoEscape
     * @return void
     */
    protected function setAutoEscape($autoEscape = true)
    {
        $this->autoEscape = $autoEscape;
    }

    /**
     *
     *
     * @return void
     */
    protected function setNoAutoEscape()
    {
        $this->autoEscape = false;
    }

    /**
     * Renders provided string according to current escaping rule.
     *
     * @param string $content
     * @return void
     */
    protected function write($content)
    {
        if ($this->autoEscape) {
            $this->escape($content);
        } else {
            $this->raw($content);
        }
    }

    /**
     * Renders provided string with escaping.
     *
     * @param string $content
     * @return void
     */
    protected function escape($content)
    {
        echo $this->getEscaped($content);
    }

    /**
     * Returns provided string with escaping.
     *
     * @param string $content
     * @return string
     */
    protected function getEscaped($content)
    {
        return $this->escaper->escape($content);
    }

    /**
     * Renders provided string without escaping.
     *
     * @param string $content
     * @return void
     */
    protected function raw($content)
    {
        echo $content;
    }
}
