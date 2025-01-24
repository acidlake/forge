<?php
namespace Base\Providers;

use Base\Controllers\BaseApiController;
use Base\Core\Container;
use Base\Interfaces\BaseApiControllerInterface;
use Base\Interfaces\ProviderInterface;
use Base\Interfaces\RequestInterface;
use Base\Interfaces\ResponseInterface;
use Base\Interfaces\RouterInterface;
use Base\Router\Http\Request;
use Base\Router\Http\Response;
use Base\Router\Router;

class RouterServiceProvider implements ProviderInterface
{
    public function register(Container $container): void
    {
        $container->bind(RouterInterface::class, fn() => new Router());

        $router = $container->resolve(RouterInterface::class);
        error_log(get_class($router));

        $container->bind(RequestInterface::class, fn() => new Request());
        $container->bind(ResponseInterface::class, fn() => new Response());

        $container->bind(BaseApiControllerInterface::class, function () {
            return new BaseApiController();
        });
    }
}
