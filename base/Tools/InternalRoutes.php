<?php
namespace Base\Tools;

use Base\Interfaces\RouterInterface;

class InternalRoutes
{
    public static function register(RouterInterface $router): void
    {
        $router->get("/_internal/uptime", function () {
            echo json_encode(["status" => "ok", "uptime" => time()]);
        });
    }
}
