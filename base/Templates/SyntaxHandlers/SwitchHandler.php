<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class SwitchHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            '/\{#switch\s+([\$\w]+)\}(.*?)\{:default\}(.*?)\{\/switch\}/s',
            function ($matches) {
                $variable = $matches[1];
                $cases = $matches[2];
                $default = $matches[3];

                $phpCode = "<?php switch ($variable): ?>";
                $phpCode .= preg_replace(
                    "/\{#case\s+(.*?)\}(.*?)/s",
                    "<?php case $1: ?>$2<?php break; ?>",
                    $cases
                );
                $phpCode .= "<?php default: ?>$default<?php endswitch; ?>";

                return $phpCode;
            },
            $content
        );
    }
}
