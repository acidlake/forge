<?php
/**
 * Entry point for the Forge framework.
 *
 * This file bootstraps the framework, initializes the Dependency Injection (DI) container,
 * loads service providers, and dispatches the router to handle incoming requests.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */

define("BASE_PATH", dirname(__DIR__)); // Define the base path for the application.
define("VIEW_PATH", BASE_PATH . "/app/Views/"); // Define the path for view templates.

// Autoload framework and application classes
require_once "../base/Core/Autoloader.php";
Base\Core\Autoloader::register();

use Base\Core\ContainerHelper;
use Base\Core\ProviderLoader;
use Base\Core\CoreServiceProvider;
use Base\Core\RouteLoader;
use Base\Interfaces\RouterInterface;
use Base\Tools\InternalRoutes;

// Get the Dependency Injection (DI) container
$container = ContainerHelper::getContainer();

/**
 * Load and register the core service provider.
 *
 * The CoreServiceProvider registers essential services required by the framework.
 */
$coreProvider = new CoreServiceProvider();
$coreProvider->register($container);

/**
 * Load and register application-specific service providers.
 *
 * ProviderLoader dynamically discovers and registers service providers defined in the application.
 */
ProviderLoader::load($container);

/**
 * Resolve the router instance from the DI container.
 *
 * The router handles the routing of incoming HTTP requests.
 *
 * @var RouterInterface $router
 */
$router = $container->resolve(RouterInterface::class);

/**
 * Register internal framework-specific routes.
 *
 * Internal routes are used for framework operations such as monitoring and diagnostics.
 */
InternalRoutes::register($router);

/**
 * Load and register application-specific routes.
 *
 * RouteLoader dynamically discovers and registers routes defined in the application.
 */
RouteLoader::load($router);

/**
 * Dispatch the router to handle the incoming request.
 *
 * The router resolves the appropriate route handler and executes it.
 */
$router->dispatch();
