<?php

namespace perf\Vc\Templating;

/**
 * Template base implementation.
 */
abstract class TemplateBase implements TemplateInterface
{

    /**
     *
     *
     * @var EscaperInterface
     */
    private $escaper;

    /**
     * Path to the template.
     *
     * @var string
     */
    private $path;

    /**
     * Template parameters.
     *
     * @var {string:mixed}
     */
    private $parameters = array();

    /**
     * Parent template being extended.
     *
     * @var null|Template
     */
    private $extendedTemplate;

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
     * @var bool
     */
    private $autoEscape = true;

    /**
     * Constructor.
     *
     * @param EscaperInterface $escaper
     * @param string           $path       Path to template.
     * @param {string:mixed}   $parameters Optional template parameters.
     */
    public function __construct(EscaperInterface $escaper, $path, array $parameters = array())
    {
        $this->escaper    = $escaper;
        $this->path       = $path;
        $this->parameters = $parameters;
    }

    /**
     * Tells whether a template parameter is set or not.
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
     * Sets a template parameter.
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
     * Unsets a template parameter.
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
     * Returns the value of a template parameter.
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
     * Builds the template content into a string.
     *
     * @return string
     * @throws \RuntimeException
     */
    public function fetch()
    {
        $outputBufferringStartLevel = ob_get_level();

        ob_start();

        try {
            $this->includeTemplate();
        } catch (\Exception $e) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            $message = "Failed to render template ({$this->path}). << {$e->getMessage()}";

            throw new \RuntimeException($message, 0, $e);
        }

        if (null !== $this->currentSlotId) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            $message = "Failed to render template ({$this->path}): slot '{$this->currentSlotId}' not ended.";

            $this->currentSlotId = null;

            throw new \RuntimeException($message);
        }

        if ($this->extendedTemplate) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            return $this->extendedTemplate->fetch();
        }

        return ob_get_clean();
    }

    /**
     *
     *
     * @return void
     * @throws \Exception
     */
    abstract protected function includeTemplate();

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
     * Extends another template.
     *
     * @param string $path Path to template to extend.
     * @return void
     * @throws \RuntimeException
     */
    protected function extend($path)
    {
        if (null !== $this->extendedTemplate) {
            throw new \RuntimeException('A template is already being extended.');
        }

        $this->extendedTemplate = new Template($this->escaper, $path, $this->parameters);
        $this->extendedTemplate->slots = $this->slots;
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
        if (!$this->extendedTemplate) {
            throw new \RuntimeException("Cannot begin slot: not extending a template.");
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

        $this->extendedTemplate->setSlotContent($this->currentSlotId, ob_get_clean());

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

        if ($this->extendedTemplate) {
            $this->extendedTemplate->setSlotContent($slotId, $content);
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
     * Includes another template.
     *
     * @param string $path Path to sub-template.
     * @param {string:mixed} $childParameters Sub-template parameters.
     * @return void
     * @throws \RuntimeException
     */
    protected function embed($path, array $childParameters = array())
    {
        $subTemplateFullPath = dirname($this->path) . '/' . $path;

        $parameters = array_replace(
            $this->parameters,
            $childParameters
        );

        $subTemplate = new Template($this->escaper, $subTemplateFullPath, $parameters);

        try {
            echo $subTemplate->getContent();
        } catch (\Exception $e) {
            $message = "Failed to embed sub-template at '{$subTemplate->path}'. << {$e->getMessage()}";

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

    /**
     * Returns the path to the template.
     *
     * @return string
     */
    protected function getPath()
    {
        return $this->path;
    }
}
