<?php

namespace Base\Templates;

use Base\Templates\View;
use RuntimeException;

/**
 * ViewEngine provides a basic implementation of the TemplateEngine interface.
 *
 * This class is responsible for rendering view templates with support for custom syntax preprocessing.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 * @copyright 2025
 */
class ViewEngine implements View
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
     * Array to hold custom paths for specific templates.
     *
     * @var array
     */
    private array $customPaths = [];

    /**
     * Constructor for ViewEngine.
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
     * @param string $template The template name.
     *
     * @return string The resolved file path for the template.
     */
    private function resolvePath(string $template): string
    {
        $templatePath = str_replace(".", "/", $template);

        foreach ($this->customPaths as $namespace => $path) {
            if (str_starts_with($template, $namespace)) {
                // If the template equals the namespace (e.g., "errors")
                if ($template === $namespace) {
                    return $path . $namespace . "/index.php"; // Match the folder's index.php
                }

                // If the template is nested (e.g., "errors.default"), include the relative path
                $relativePath = substr($templatePath, strlen($namespace) + 1); // +1 for the dot separator
                return $path . $namespace . "/" . $relativePath . ".php";
            }
        }

        // Fallback to default path
        return $this->viewPath . $templatePath . ".php";
    }

    /**
     * Set a custom path for a specific namespace or template.
     *
     * @param string $namespace The namespace (e.g., 'errors').
     * @param string $path      The custom path for the namespace.
     */
    public function setCustomPath(string $namespace, string $path): void
    {
        $this->customPaths[rtrim($namespace, ".")] = rtrim($path, "/") . "/";
    }
}
