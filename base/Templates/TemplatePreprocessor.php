<?php

namespace Base\Templates;

use Base\Templates\SyntaxHandlers\ComponentHandler;
use Base\Templates\SyntaxHandlers\IfHandler;
use Base\Templates\SyntaxHandlers\EachHandler;
use Base\Templates\SyntaxHandlers\PartialHandler;
use Base\Templates\SyntaxHandlers\VariableHandler;
use Base\Templates\SyntaxHandlers\FunctionHandler;
use Base\Templates\SyntaxHandlers\AttributeHandler;
use Base\Templates\SyntaxHandlers\DefaultValueHandler;
use Base\Templates\SyntaxHandlers\EscapeHandler;
use Base\Templates\SyntaxHandlers\SetVariableHandler;
use Base\Templates\SyntaxHandlers\SwitchHandler;

/**
 * TemplatePreprocessor processes templates by applying syntax handlers.
 *
 * This class iterates through registered syntax handlers to transform the template content
 * based on custom syntax and dynamic data.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @autor Jeremias
 * @copyright 2025
 */
class TemplatePreprocessor
{
    /**
     * List of syntax handlers to process the template content.
     *
     * @var array
     */
    private array $handlers = [];

    /**
     * Constructor for TemplatePreprocessor.
     *
     * Initializes the list of syntax handlers used for processing templates.
     */
    public function __construct()
    {
        $this->handlers = [
            new IfHandler(),
            new EachHandler(),
            new VariableHandler(),
            new PartialHandler(),
            new FunctionHandler(),
            new AttributeHandler(),
            new DefaultValueHandler(),
            new EscapeHandler(),
            new SetVariableHandler(),
            new SwitchHandler(),
            new ComponentHandler(),
        ];
    }

    /**
     * Process the template content using registered syntax handlers.
     *
     * Each handler modifies the template content by processing specific syntax rules.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template processing.
     *
     * @return string The processed template content.
     */
    public function process(string $content, array $data): string
    {
        foreach ($this->handlers as $handler) {
            $content = $handler->process($content, $data);
        }

        return $content;
    }
}
