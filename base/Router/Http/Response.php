<?php
namespace Base\Router\Http;

use Base\Interfaces\ResponseInterface;
use Base\Interfaces\ViewInterface;

class Response implements ResponseInterface
{
    /**
     * Send a response in a specific format.
     */
    public static function send(
        mixed $data,
        string $format = "json",
        int $status = 200,
        ?string $view = null,
        ?ViewInterface $renderer = null
    ): void {
        switch ($format) {
            case "text":
                self::text(self::prepareText($data), $status);
                break;

            case "html":
                if (!$renderer) {
                    throw new \InvalidArgumentException(
                        "View renderer is required for HTML format."
                    );
                }
                self::html($view, $data, $renderer, $status);
                break;

            case "xml":
                self::xml(self::prepareArray($data), $status);
                break;

            case "csv":
                self::csv(self::prepareArray($data), $status);
                break;

            case "json":
            default:
                self::json(self::prepareArray($data), $status);
                break;
        }
    }

    /**
     * Send a JSON response.
     */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit();
    }

    /**
     * Send a plain text response.
     */
    public static function text(string $message, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: text/plain");
        echo $message;
        exit();
    }

    /**
     * Send an HTML response using the view renderer.
     */
    public static function html(
        string $view,
        mixed $data,
        ViewInterface $renderer,
        int $status = 200
    ): void {
        http_response_code($status);
        header("Content-Type: text/html");
        echo $renderer->render($view, self::prepareArray($data));
        exit();
    }

    /**
     * Send an XML response.
     */
    public static function xml(array $data, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/xml");

        $xml = new \SimpleXMLElement("<response/>");
        self::arrayToXml($data, $xml);

        echo $xml->asXML();
        exit();
    }

    /**
     * Send a CSV response.
     */
    public static function csv(array $data, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"data.csv\"");

        $output = fopen("php://output", "w");
        foreach ($data as $row) {
            $row = self::prepareArray($row); // Ensure every row is an array
            fputcsv($output, is_array($row) ? $row : [$row]);
        }
        fclose($output);
        exit();
    }

    /**
     * Convert an array to XML recursively.
     */
    private static function arrayToXml(
        array $data,
        \SimpleXMLElement &$xml
    ): void {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subNode = $xml->addChild(is_numeric($key) ? "item$key" : $key);
                self::arrayToXml($value, $subNode);
            } else {
                $xml->addChild(
                    is_numeric($key) ? "item$key" : $key,
                    htmlspecialchars((string) $value)
                );
            }
        }
    }

    /**
     * Prepare data for text format.
     */
    private static function prepareText(mixed $data): string
    {
        if (is_array($data)) {
            return implode(
                "\n",
                array_map(
                    fn($item) => json_encode($item, JSON_PRETTY_PRINT),
                    $data
                )
            );
        }
        return is_object($data)
            ? json_encode($data, JSON_PRETTY_PRINT)
            : (string) $data;
    }

    /**
     * Prepare data as an array.
     */
    private static function prepareArray(mixed $data): array
    {
        if (is_object($data)) {
            return json_decode(json_encode($data), true); // Convert object to array
        }
        if (!is_array($data)) {
            return [$data];
        }
        return array_map(
            fn($item) => is_object($item)
                ? json_decode(json_encode($item), true)
                : $item,
            $data
        );
    }
}
