<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * FunctionHandler processes custom syntax for calling PHP functions in templates.
 *
 * Handles expressions in the format `{functionName(params)}` to dynamically
 * invoke PHP functions within templates.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class FunctionHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{functionName(params)}` syntax.
     *
     * Matches and replaces `{functionName(params)}` with PHP code that dynamically
     * invokes the specified function with the provided parameters.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with function call syntax replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{([\w_]+)\((.*?)\)\}/",
            function ($matches) {
                $function = $matches[1];
                $params = $matches[2];
                return "<?php echo {$function}({$params}); ?>";
            },
            $content
        );
    }
}
