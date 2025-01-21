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

class TemplatePreprocessor
{
    private array $handlers = [];

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
            new ComponentHandler(),
        ];
    }

    public function process(string $content, array $data): string
    {
        foreach ($this->handlers as $handler) {
            $content = $handler->process($content, $data);
        }

        return $content;
    }
}
