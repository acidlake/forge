<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\ViewInterface;
use Base\Interfaces\SyntaxHandlerInterface;

class IncludeHandler implements SyntaxHandlerInterface
{
    private ViewInterface $view;

    public function __construct(ViewInterface $view)
    {
        $this->view = $view;
    }

    public function process(string $template, array $data): string
    {
        $pattern = '/\{#include\s+"([\w\.]+)"(?:\s*\((.*?)\))?\}/';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($data) {
                $viewPath = $matches[1];
                $params = [];

                if (!empty($matches[2])) {
                    $params = $this->parseParams($matches[2], $data);
                }

                return $this->view->render($viewPath, $params);
            },
            $template
        );
    }

    private function parseParams(string $paramString, array $data): array
    {
        $paramString = preg_replace_callback(
            '/\$(\w+)/',
            function ($varMatches) use ($data) {
                $varName = $varMatches[1];
                return $data[$varName] ?? "null";
            },
            $paramString
        );

        $parsedParams = eval("return [$paramString];");

        if (!is_array($parsedParams)) {
            throw new \RuntimeException(
                "Invalid parameters in include statement: $paramString"
            );
        }

        return $parsedParams;
    }
}
