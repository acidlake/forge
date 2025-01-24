<?php

namespace Base\Templates;

use RuntimeException;

/**
 * DefaultViewEngine provides a basic implementation of the TemplateEngine interface.
 *
 * This class is responsible for rendering view templates with support for custom syntax preprocessing.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class DefaultViewEngine implements TemplateEngine
{
    /**
     * Path to the directory containing view templates.
     *
     * @var string
     */
    private string $viewPath;

    /**
     * Template preprocessor for handling custom syntax.
     *
     * @var TemplatePreprocessor
     */
    private TemplatePreprocessor $preprocessor;

    /**
     * Constructor for DefaultViewEngine.
     *
     * @param string $viewPath The base path to the view templates.
     */
    public function __construct(string $viewPath)
    {
        $this->viewPath = rtrim($viewPath, "/") . "/";
        $this->preprocessor = new TemplatePreprocessor();
    }

    /**
     * Render a view template with the provided data.
     *
     * @param string $template The template file to render (e.g., 'home.index').
     * @param array  $data     An associative array of data to pass to the template.
     *
     * @throws RuntimeException If the template file is not found.
     *
     * @return string The rendered output of the template.
     */
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

    /**
     * Resolve the file path for a given template name.
     *
     * Converts dot notation (e.g., 'home.index') to a directory structure
     * and appends the '.php' extension.
     *
     * @param string $template The template name.
     *
     * @return string The resolved file path for the template.
     */
    private function resolvePath(string $template): string
    {
        $template = str_replace(".", "/", $template);
        return $this->viewPath . $template . ".php";
    }
}
