<?php

namespace perf\Vc;

use DomainException;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class View implements ViewInterface
{
    /**
     * Path to the view file.
     *
     * @var string
     */
    private $viewPath;

    /**
     * View variables.
     *
     * @var {string:mixed}
     */
    private $vars = array();

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
     * @var Escaper
     */
    private $escaper;

    /**
     * @var bool
     */
    private $autoEscape = true;

    /**
     * @param string $viewPath Path to the view file.
     * @param {string:mixed} $vars Optional view variables.
     */
    public function __construct($viewPath, array $vars = array())
    {
        $this->setViewPath($viewPath);

        // @xxx
        foreach ($vars as $var => $value) {
            $this->__set($var, $value);
        }

        // @xxx
        $this->setEscaper(new HtmlEscaper());
    }

    /**
     *
     *
     * @param Escaper $escaper
     * @return void
     */
    public function setEscaper(Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    /**
     * Sets the path to the view file.
     *
     * @param string $viewPath Path to the view file.
     * @return View Fluent return.
     * @throws InvalidArgumentException
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;

        return $this;
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
     * Tells whether a view variable is set or not.
     * Magic method.
     *
     * @param string $var Name of the variable.
     * @return bool
     */
    public function __isset($var)
    {
        return isset($this->vars[$var]);
    }

    /**
     * Sets a view variable.
     * Magic method.
     *
     * @param string $var Name of the variable.
     * @param mixed $value Value of the variable.
     * @return void
     */
    public function __set($var, $value)
    {
        $this->vars[$var] = $value;
    }

    /**
     * Unsets a view variable.
     * Magic method.
     *
     * @param string $var Name of the variable.
     * @return void
     */
    public function __unset($var)
    {
        unset($this->vars[$var]);
    }

    /**
     * Returns the value of a view variable.
     * Magic method.
     *
     * @param string $var Name of the variable.
     * @return mixed
     * @throws DomainException
     */
    public function &__get($var)
    {
        if (array_key_exists($var, $this->vars)) {
            return $this->vars[$var];
        }

        throw new DomainException("Variable '{$var}' does not exist.");
    }

    /**
     * Builds and renders the view content to the user.
     *
     * @return void
     * @throws RuntimeException
     */
    private function render()
    {
        echo $this->fetch();
    }

    /**
     * Builds the view content into a string.
     *
     * @return string
     * @throws RuntimeException
     */
    public function fetch()
    {
        $outputBufferringStartLevel = ob_get_level();

        ob_start();

        try {
            $this->includeViewFile();
        } catch (Exception $e) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            $message = "Failed to render view ({$this->viewPath}). << {$e->getMessage()}";

            throw new RuntimeException($message, 0, $e);
        }

        if (!is_null($this->currentSlotId)) {
            $this->fixOutputBufferringStack($outputBufferringStartLevel);

            $message = "Failed to render view ({$this->viewPath}): slot '{$this->currentSlotId}' not ended.";

            $this->currentSlotId = null;

            throw new RuntimeException($message);
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
     * @throws Exception
     */
    private function includeViewFile()
    {
        if (!is_readable($this->viewPath)) {
            throw new RuntimeException("Expected view file not found ({$this->viewPath}).");
        }

        require($this->viewPath);
    }

    /**
     *
     *
     * @param int $outputBufferringStartLevel
     * @return void
     * @throws RuntimeException
     */
    private function fixOutputBufferringStack($outputBufferringStartLevel)
    {
        if (ob_get_level() < $outputBufferringStartLevel) {
            throw new RuntimeException('Failed to fix output bufferingf stack.');
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
     * @throws RuntimeException
     */
    private function extend($extendedViewPath)
    {
        if (!is_null($this->extendedView)) {
            throw new RuntimeException('A view is already being extended.');
        }

        $this->extendedView = new self($extendedViewPath, $this->vars);
        $this->extendedView->slots = $this->slots;
    }

    /**
     *
     *
     * @param string $slotId Slot unique identifier.
     * @return void
     * @throws RuntimeException
     */
    private function beginSlot($slotId)
    {
        if (!$this->extendedView) {
            throw new RuntimeException("Cannot begin slot: not extending a view.");
        }

        if (!is_null($this->currentSlotId)) {
            throw new RuntimeException("Cannot begin slot: a slot is already begun.");
        }

        $this->currentSlotId = $slotId;

        ob_start();
    }

    /**
     *
     *
     * @return void
     * @throws RuntimeException
     */
    private function endSlot()
    {
        if (is_null($this->currentSlotId)) {
            throw new RuntimeException('Cannot end slot: no slot is begun.');
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
    private function renderSlot($slotId)
    {
        if (array_key_exists($slotId, $this->slots)) {
            echo $this->slots[$slotId];
        }
    }

    /**
     * Includes another view file.
     *
     * @param string $subViewPath Path to sub-view.
     * @param {string:mixed} $childVars Sub-view variables.
     * @return void
     * @throws RuntimeException
     */
    private function embed($subViewPath, array $childVars = array())
    {
        $subViewFullPath = dirname($this->viewPath) . '/' . $subViewPath;

        $vars = array_merge(
            $this->vars,
            $childVars
        );

        $subView = new self($subViewFullPath, $vars);

        try {
            $subView->render();
        } catch (Exception $e) {
            $message = "Failed to embed sub-view at '{$subView->viewPath}'. << {$e->getMessage()}";

            throw new RuntimeException($message, 0, $e);
        }
    }

    /**
     *
     *
     * @param bool $escape
     * @return void
     */
    private function autoEscape($escape = true)
    {
        $this->autoEscape = $escape;
    }

    /**
     *
     *
     * @return void
     */
    private function noAutoEscape()
    {
        $this->autoEscape = false;
    }

    /**
     * Renders provided string according to current escaping rule.
     *
     * @param string $content
     * @return void
     */
    private function write($content)
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
    private function escape($content)
    {
        echo $this->getEscaped($content);
    }

    /**
     * Returns provided string with escaping.
     *
     * @param string $content
     * @return string
     */
    private function getEscaped($content)
    {
        return $this->escaper->escape($content);
    }

    /**
     * Renders provided string without escaping.
     *
     * @param string $content
     * @return void
     */
    private function raw($content)
    {
        echo $content;
    }
}
