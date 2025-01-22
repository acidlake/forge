<?php
namespace Base\Router\Middleware;

use Base\Interfaces\MiddlewareInterface;
use Base\Router\Http\Request;

class ApiVersionMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next)
    {
        $versionHeader = $request->header("Accept", null);

        if (
            $versionHeader &&
            preg_match("/vnd\.forge\.v(\d+)\+json/", $versionHeader, $matches)
        ) {
            $request->version = "v" . $matches[1];
        } else {
            $request->version = "v1";
        }

        return $next($request);
    }
}
