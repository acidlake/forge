<?php
namespace Base\Exceptions;

use Base\Core\ContainerHelper;
use Base\Router\Http\Response;
use Base\Interfaces\ViewInterface;
use Throwable;

class ExceptionHandler
{
    public static function handle(Throwable $exception): void
    {
        $isApi = str_starts_with($_SERVER["REQUEST_URI"] ?? "", "/api");

        self::logException($exception);

        if ($isApi) {
            Response::json(
                [
                    "success" => false,
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                    "errors" => [
                        "file" => $exception->getFile(),
                        "line" => $exception->getLine(),
                    ],
                ],
                500
            );
        } else {
            self::renderErrorPage($exception);
        }
    }

    private static function logException(Throwable $exception): void
    {
        error_log(
            $exception->getMessage() .
                " in " .
                $exception->getFile() .
                " on line " .
                $exception->getLine()
        );
    }

    private static function renderErrorPage(Throwable $exception): void
    {
        $view = ContainerHelper::getContainer()->resolve(ViewInterface::class);

        // Collect additional information
        $trace = array_map(function ($frame) {
            if (isset($frame["file"], $frame["line"])) {
                $frame["code"] = self::getCodeSnippet(
                    $frame["file"],
                    $frame["line"]
                );
            }
            return $frame;
        }, $exception->getTrace());

        $executionTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
        $memoryUsage = memory_get_usage();

        // Render the error page
        echo $view->render("errors.default", [
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $trace,
            "executionTime" => $executionTime,
            "memoryUsage" => $memoryUsage,
        ]);

        exit();
    }

    private static function getCodeSnippet(
        string $file,
        int $line,
        int $padding = 5
    ): string {
        if (!file_exists($file)) {
            return "";
        }

        $lines = file($file);
        $start = max($line - $padding - 1, 0);
        $end = min($line + $padding - 1, count($lines) - 1);

        $snippet = "";
        for ($i = $start; $i <= $end; $i++) {
            $currentLine = $i + 1;
            $highlight = $currentLine === $line ? ">> " : "   ";
            $snippet .=
                $highlight .
                str_pad($currentLine, 4, " ", STR_PAD_LEFT) .
                " | " .
                $lines[$i];
        }

        return $snippet;
    }
}
