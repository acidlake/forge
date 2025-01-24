<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * FunctionHandler processes standard PHP function calls in templates.
 *
 * Handles expressions like `{functionName(params)}` but excludes
 * functions that belong to the `Base\Helpers` namespace or other custom handlers.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class FunctionHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            // Match function calls excluding custom namespace helpers
            "/\{(?!Base\\\\Helpers\\\\)([\w_]+)\((.*?)\)\}/",
            function ($matches) {
                $function = $matches[1];
                $params = $matches[2];

                // Return PHP code for standard PHP functions
                return "<?php echo {$function}({$params}); ?>";
            },
            $content
        );
    }
}
