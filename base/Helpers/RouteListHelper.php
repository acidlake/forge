<?php
namespace Base\Helpers;

class RouteListHelper
{
    public function renderRouteList(array $routes): void
    {
        // Header for the table
        $header = ["Method", "URI", "Name", "Handler", "Middleware"];

        // Collect route data
        $rows = array_map(function ($route) {
            $handler = $this->formatHandler($route["handler"]);
            $middleware = $this->formatMiddleware($route["middleware"]);
            $name = $route["name"] ?? "None";
            $uri = $this->extractOriginalPattern($route["pattern"]);

            return [$route["method"], $uri, $name, $handler, $middleware];
        }, $routes);

        $this->renderTable($header, $rows);
    }

    public function formatHandler(mixed $handler): string
    {
        if (is_array($handler)) {
            return implode("@", $handler);
        }

        if (is_callable($handler)) {
            return "Closure";
        }

        return (string) $handler;
    }

    public function formatMiddleware(array $middleware): string
    {
        if (empty($middleware)) {
            return "None";
        }

        return implode(
            ", ",
            array_map(function ($mw) {
                if (is_object($mw)) {
                    return get_class($mw);
                }

                if (is_callable($mw)) {
                    return "Closure";
                }

                return "Unknown";
            }, $middleware)
        );
    }

    public function extractOriginalPattern(string $regex): string
    {
        $pattern = preg_replace_callback(
            "/\(\?P<(\w+)>\[\\\^\/\]\+\)/",
            function ($matches) {
                return "{" . $matches[1] . "}";
            },
            $regex
        );

        $pattern = trim($pattern, "#^$");
        $pattern = str_replace("\\/", "/", $pattern);

        return $pattern;
    }

    public function renderTable(array $header, array $rows): void
    {
        $columnWidths = array_map(function ($column) use ($header, $rows) {
            $maxLength = strlen($header[$column]);
            foreach ($rows as $row) {
                $maxLength = max($maxLength, strlen($row[$column]));
            }
            return $maxLength;
        }, array_keys($header));

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
