<?php
define("BASE_PATH", dirname(__DIR__));

define("VIEW_PATH", BASE_PATH . "/app/Views/");

require_once "../base/Core/Autoloader.php";

Base\Core\Autoloader::register();

use Base\Core\ContainerHelper;
use Base\Core\ProviderLoader;
use Base\Core\CoreServiceProvider;
use Base\Core\RouteLoader;
use Base\Core\RouterInterface;
use Base\Tools\InternalRoutes;

// Get DI Container
$container = ContainerHelper::getContainer();

// Load and register service providers
$coreProvider = new CoreServiceProvider();
$coreProvider->register($container);

// Load application-spesific service providers
ProviderLoader::load($container);

// Resolve router
$router = $container->resolve(RouterInterface::class);

// Register internal routes
InternalRoutes::register($router);

// Register application-spesific routes
RouteLoader::load($router);

// Dispatch the router
$router->dispatch();
