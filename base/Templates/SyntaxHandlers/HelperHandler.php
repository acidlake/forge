<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

/**
 * HelperHandler processes custom syntax for calling static methods in helpers.
 *
 * Handles expressions in the format `{HelperName::method(params)}` to dynamically
 * invoke static methods from PHP classes within templates.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class HelperHandler implements SyntaxHandlerInterface
{
    /**
     * Process the template content to handle `{HelperName::method(params)}` syntax.
     *
     * Matches and replaces `{HelperName::method(params)}` with PHP code that dynamically
     * invokes the specified static method with the provided parameters.
     *
     * @param string $content The template content to process.
     * @param array  $data    An associative array of dynamic data for template rendering (not used in this handler).
     *
     * @return string The processed template content with static method calls replaced by PHP code.
     */
    public function process(string $content, array $data): string
    {
        return preg_replace_callback(
            "/\{([\w\\\\]+)::([\w_]+)\((.*?)\)\}/",
            function ($matches) {
                $class = $matches[1];
                $method = $matches[2];
                $params = $matches[3];

                // Safeguard to prevent invalid or malicious class/method calls
                if (!class_exists($class)) {
                    return "<!-- Error: Class {$class} not found -->";
                }

                if (!method_exists($class, $method)) {
                    return "<!-- Error: Method {$method} does not exist in {$class} -->";
                }

                return "<?php echo {$class}::{$method}({$params}); ?>";
            },
            $content
        );
    }
}
