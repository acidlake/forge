<?php

namespace Base\Commands;

use Base\Core\RouterHelper;
use Base\Interfaces\CommandInterface;
use Base\Interfaces\RouterInterface;

class RouteListCommand implements CommandInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getName(): string
    {
        return "route:list";
    }

    public function getDescription(): string
    {
        return "Display a list of registered routes.";
    }

    public function execute(array $args = []): void
    {
        $router = RouterHelper::getRouter();
        $routes = $router->getRoutes();

        if (empty($routes)) {
            echo "No routes have been registered.\n";
            error_log(print_r($this->router, true)); // Debug router instance
            return;
        }

        // Header for the table
        $header = ["Method", "URI", "Handler", "Middleware"];

        // Collect route data
        $rows = [];
        foreach ($routes as $route) {
            $handler = is_array($route["handler"])
                ? implode("@", $route["handler"])
                : (is_callable($route["handler"])
                    ? "Closure"
                    : $route["handler"]);

            $middleware = !empty($route["middleware"])
                ? implode(", ", array_map("get_class", $route["middleware"]))
                : "None";

            $rows[] = [
                $route["method"],
                $route["pattern"],
                $handler,
                $middleware,
            ];
        }

        // Render the table
        $this->renderTable($header, $rows);
    }

    private function renderTable(array $header, array $rows): void
    {
        // Calculate column widths
        $columnWidths = array_map(function ($column) use ($header, $rows) {
            $maxLength = strlen($header[$column]);
            foreach ($rows as $row) {
                $maxLength = max($maxLength, strlen($row[$column]));
            }
            return $maxLength;
        }, array_keys($header));

        // Render the header
        $line =
            "+-" .
            implode(
                "-+-",
                array_map(fn($width) => str_repeat("-", $width), $columnWidths)
            ) .
            "-+";
        echo $line . "\n";
        echo "| " .
            implode(
                " | ",
                array_map(
                    fn($col, $width) => str_pad($header[$col], $width),
                    array_keys($header),
                    $columnWidths
                )
            ) .
            " |\n";
        echo $line . "\n";

        // Render the rows
        foreach ($rows as $row) {
            echo "| " .
                implode(
                    " | ",
                    array_map(
                        fn($col, $width) => str_pad($row[$col], $width),
                        array_keys($header),
                        $columnWidths
                    )
                ) .
                " |\n";
        }
        echo $line . "\n";
    }
}
