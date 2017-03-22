<?php

namespace perf\Vc\Templating;

use perf\Vc\Templating\Plugin\PluginInterface;

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
     * Template variables.
     *
     * @var {string:mixed}
     */
    private $variables = array();

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
     *
     *
     * @var {string:PluginInterface}
     */
    private $plugins = array();

    /**
     * Constructor.
     *
     * @param EscaperInterface $escaper
     * @param string           $path      Path to template.
     * @param {string:mixed}   $variables Optional template variables.
     */
    public function __construct(EscaperInterface $escaper, $path, array $variables = array())
    {
        $this->escaper   = $escaper;
        $this->path      = $path;
        $this->variables = $variables;
    }

    /**
     * @param PluginInterface $plugin
     * @return void
     */
    public function addPlugin(PluginInterface $plugin)
    {
        $this->plugins[$plugin->getName()] = $plugin;
    }

    /**
     * Tells whether a template variable is set or not.
     * Magic method.
     *
     * @param string $name Name of the variable.
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * Sets a template variable.
     * Magic method.
     *
     * @param string $name Name of the variable.
     * @param mixed  $value Value of the variable.
     * @return void
     */
    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * Unsets a template variable.
     * Magic method.
     *
     * @param string $name Name of the variable.
     * @return void
     */
    public function __unset($name)
    {
        unset($this->variables[$name]);
    }

    /**
     * Invoke plugin.
     * Magic method.
     *
     * @param string $method
     * @param array  $arguments
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        if (!array_key_exists($method, $this->plugins)) {
            throw new \BadMethodCallException("No plugin with name '{$method}'.");
        }

        return $this->plugins[$method]->execute($arguments);
    }

    /**
     * Returns the value of a template variable.
     * Magic method.
     *
     * @param string $name Name of the variable.
     * @return mixed
     * @throws \DomainException
     */
    public function &__get($name)
    {
        if (array_key_exists($name, $this->variables)) {
            return $this->variables[$name];
        }

        throw new \DomainException("Template variable '{$name}' is not defined in template at {$this->path}.");
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

        $this->extendedTemplate = new Template($this->escaper, $path, $this->variables);
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
