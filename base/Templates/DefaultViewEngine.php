<?php

namespace Base\Templates;

use RuntimeException;

/**
 * DefaultViewEngine provides a basic implementation of the TemplateEngine interface.
 */
class DefaultViewEngine implements TemplateEngine
{
    private string $viewPath;
    private TemplatePreprocessor $preprocessor;

    public function __construct(string $viewPath)
    {
        $this->viewPath = rtrim($viewPath, "/") . "/";
        $this->preprocessor = new TemplatePreprocessor();
    }

    public function render(string $template, array $data = []): string
    {
        $filePath = $this->resolvePath($template);

        if (!file_exists($filePath)) {
            throw new RuntimeException("View file not found: {$filePath}");
        }

        // Preprocess the template to handle custom syntax
        $processedContent = $this->preprocessor->process(
            file_get_contents($filePath),
            $data
        );

        // Save the preprocessed template to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), "forge_view_");
        file_put_contents($tempFile, $processedContent);

        // Extract data for use in the template
        extract($data);

        // Capture the output of the processed template
        ob_start();
        include $tempFile;
        $output = ob_get_clean();

        // Clean up the temporary file
        unlink($tempFile);

        return $output;
    }

    private function resolvePath(string $template): string
    {
        $template = str_replace(".", "/", $template);
        return $this->viewPath . $template . ".php";
    }
}
